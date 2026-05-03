<?php

namespace App\Http\Controllers;

use App\Mail\RecipeMail;
use App\Models\Patient;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecipeController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // Index: list saved recipes
    // ──────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $recipes = Recipe::where('user_id', auth()->id())
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('recipes.index', compact('recipes', 'search'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Show: view a saved recipe + send-to-patient panel
    // ──────────────────────────────────────────────────────────────────────────
    public function show(Recipe $recipe)
    {
        abort_if($recipe->user_id !== auth()->id(), 403);

        $patients = Patient::where('user_id', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('recipes.show', compact('recipe', 'patients'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // AJAX: search DB first (< 3 results → fall back to FatSecret recipes.search)
    // Returns JSON: { db: [...], fs: [...] }
    // ──────────────────────────────────────────────────────────────────────────
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if ($q === '') {
            return response()->json(['db' => [], 'fs' => []]);
        }

        // ── 1. Search own DB ─────────────────────────────────────────────────
        $dbRecipes = Recipe::where('user_id', auth()->id())
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn($r) => [
                'id'       => $r->id,
                'name'     => $r->name,
                'calories' => $r->calories ? round($r->calories) : null,
                'fat'      => $r->fat_g,
                'carbs'    => $r->carbs_g,
                'protein'  => $r->protein_g,
                'fiber'    => $r->fiber_g,
                'serving'  => $r->serving_size,
                'image'    => $r->image_url,
                'source'   => 'db',
            ])->values()->all();

        // ── 2. FatSecret fallback if DB returned fewer than 3 hits ───────────
        $fsRecipes = [];
        if (count($dbRecipes) < 3) {
            try {
                $fsRecipes = $this->fatSecretRecipeSearch($q, 20);
            } catch (\Throwable $e) {
                Log::error('FatSecret recipe search error: ' . $e->getMessage());
            }
        }

        return response()->json(['db' => $dbRecipes, 'fs' => $fsRecipes]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // AJAX POST: import a FatSecret recipe (full detail via recipe.get) and save
    // ──────────────────────────────────────────────────────────────────────────
    public function importFatSecret(Request $request)
    {
        $data = $request->validate([
            'fatsecret_recipe_id' => 'required|string',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'image_url'           => 'nullable|url|max:500',
            'source_url'          => 'nullable|url|max:500',
        ]);

        // Return existing if already saved by this user
        $existing = Recipe::where('user_id', auth()->id())
            ->where('fatsecret_recipe_id', $data['fatsecret_recipe_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'id'   => $existing->id,
                'name' => $existing->name,
                'url'  => route('recipes.show', $existing),
                'already_existed' => true,
            ]);
        }

        // Fetch full recipe details from FatSecret
        $details  = [];
        $calories = $fat = $carbs = $protein = $fiber = $serving = null;
        $ingredients = [];
        $directions  = null;

        try {
            $details = $this->fatSecretRecipeGet($data['fatsecret_recipe_id']);

            // ── Nutrition from serving_sizes ────────────────────────────────
            $servings = $details['serving_sizes']['serving'] ?? null;
            if ($servings && !isset($servings[0])) {
                $servings = [$servings]; // single serving → wrap in array
            }
            if ($servings) {
                $s        = $servings[0];
                $serving  = ($s['serving_size'] ?? null);
                $calories = isset($s['calories'])  ? (float)$s['calories']  : null;
                $fat      = isset($s['fat'])        ? (float)$s['fat']       : null;
                $carbs    = isset($s['carbohydrate']) ? (float)$s['carbohydrate'] : null;
                $protein  = isset($s['protein'])    ? (float)$s['protein']   : null;
                $fiber    = isset($s['fiber'])       ? (float)$s['fiber']    : null;
            }

            // ── Ingredients ─────────────────────────────────────────────────
            $rawIngredients = $details['ingredients']['ingredient'] ?? [];
            if ($rawIngredients && !isset($rawIngredients[0])) {
                $rawIngredients = [$rawIngredients];
            }
            foreach ($rawIngredients as $ing) {
                $ingredients[] = trim(($ing['ingredient_description'] ?? ''));
            }
            $ingredients = array_filter($ingredients);

            // ── Directions ──────────────────────────────────────────────────
            $rawDirections = $details['directions']['direction'] ?? [];
            if ($rawDirections && !isset($rawDirections[0])) {
                $rawDirections = [$rawDirections];
            }
            usort($rawDirections, fn($a, $b) => ($a['direction_number'] ?? 0) <=> ($b['direction_number'] ?? 0));
            $directionTexts = array_map(fn($d) => $d['direction_description'] ?? '', $rawDirections);
            $directionTexts = array_filter($directionTexts);
            $directions     = implode("\n", $directionTexts) ?: null;
        } catch (\Throwable $e) {
            Log::error('FatSecret recipe.get error: ' . $e->getMessage());
            // Still save with basic info from search payload
        }

        // Fallback: parse macros from description if recipe.get failed
        if ($calories === null && !empty($data['description'])) {
            $desc = $data['description'];
            if (preg_match('/Calories[:\s]*([\d.]+)/i',   $desc, $m)) $calories = (float)$m[1];
            if (preg_match('/Fat[:\s]*([\d.]+)g/i',       $desc, $m)) $fat      = (float)$m[1];
            if (preg_match('/Carbs[:\s]*([\d.]+)g/i',     $desc, $m)) $carbs    = (float)$m[1];
            if (preg_match('/Protein[:\s]*([\d.]+)g/i',   $desc, $m)) $protein  = (float)$m[1];
        }

        $recipe = Recipe::create([
            'user_id'             => auth()->id(),
            'fatsecret_recipe_id' => $data['fatsecret_recipe_id'],
            'name'                => $data['name'],
            'description'         => $data['description'] ?? null,
            'image_url'           => $data['image_url']   ?? null,
            'source_url'          => $data['source_url']  ?? ($details['recipe_url'] ?? null),
            'serving_size'        => $serving,
            'calories'            => $calories,
            'fat_g'               => $fat,
            'carbs_g'             => $carbs,
            'protein_g'           => $protein,
            'fiber_g'             => $fiber,
            'ingredients'         => array_values($ingredients) ?: null,
            'directions'          => $directions,
        ]);

        return response()->json([
            'id'             => $recipe->id,
            'name'           => $recipe->name,
            'url'            => route('recipes.show', $recipe),
            'already_existed'=> false,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Delete a saved recipe
    // ──────────────────────────────────────────────────────────────────────────
    public function destroy(Recipe $recipe)
    {
        abort_if($recipe->user_id !== auth()->id(), 403);
        $name = $recipe->name;
        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', '"' . $name . '" deleted.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Send recipe to a patient via email
    // ──────────────────────────────────────────────────────────────────────────
    public function sendToPatient(Request $request, Recipe $recipe)
    {
        abort_if($recipe->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'note'       => 'nullable|string|max:1000',
        ]);

        $patient = Patient::findOrFail($data['patient_id']);
        abort_if($patient->user_id !== auth()->id(), 403);
        abort_if(empty($patient->email), 422, 'This patient has no email address on file.');

        // Record relationship
        $recipe->patients()->attach($patient->id, [
            'note'    => $data['note'] ?? null,
            'sent_at' => now(),
        ]);

        Mail::to($patient->email, $patient->name)
            ->send(new RecipeMail($recipe, $patient, $data['note'] ?? null));

        return back()->with('success', 'Recipe sent to ' . $patient->name . ' (' . $patient->email . ').');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // FatSecret: recipes.search
    // ──────────────────────────────────────────────────────────────────────────
    private function fatSecretRecipeSearch(string $query, int $maxResults = 20): array
    {
        $result = $this->fatSecretCall('recipes.search', [
            'search_expression' => $query,
            'max_results'       => $maxResults,
            'page_number'       => 0,
        ]);

        $recipes = $result['recipes']['recipe'] ?? [];

        // FatSecret returns a single object when only 1 result
        if (isset($recipes['recipe_id'])) {
            $recipes = [$recipes];
        }

        if (!is_array($recipes)) {
            return [];
        }

        return array_map(function ($r) {
            $desc    = $r['recipe_description'] ?? '';
            $calories = $fat = $carbs = $protein = null;
            if (preg_match('/Calories[:\s]*([\d.]+)/i',  $desc, $m)) $calories = (float)$m[1];
            if (preg_match('/Fat[:\s]*([\d.]+)g/i',      $desc, $m)) $fat      = (float)$m[1];
            if (preg_match('/Carbs[:\s]*([\d.]+)g/i',    $desc, $m)) $carbs    = (float)$m[1];
            if (preg_match('/Protein[:\s]*([\d.]+)g/i',  $desc, $m)) $protein  = (float)$m[1];

            return [
                'id'          => 'fs_' . $r['recipe_id'],
                'fs_id'       => $r['recipe_id'],
                'name'        => $r['recipe_name'] ?? 'Unknown',
                'description' => $desc,
                'image'       => $r['recipe_image'] ?? null,
                'source_url'  => $r['recipe_url']   ?? null,
                'calories'    => $calories,
                'fat'         => $fat,
                'carbs'       => $carbs,
                'protein'     => $protein,
                'source'      => 'fatsecret',
            ];
        }, $recipes);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // FatSecret: recipe.get (full detail)
    // ──────────────────────────────────────────────────────────────────────────
    private function fatSecretRecipeGet(string $recipeId): array
    {
        $result = $this->fatSecretCall('recipe.get', [
            'recipe_id' => $recipeId,
        ]);

        return $result['recipe'] ?? [];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Shared OAuth 1.0 signed request to FatSecret API
    // ──────────────────────────────────────────────────────────────────────────
    private function fatSecretCall(string $method, array $extraParams = []): array
    {
        $key    = config('services.fatsecret.key');
        $secret = config('services.fatsecret.secret');

        if (!$key || !$secret) {
            return [];
        }

        $endpoint = 'https://platform.fatsecret.com/rest/server.api';

        $oauthParams = [
            'oauth_consumer_key'     => $key,
            'oauth_nonce'            => md5(uniqid((string)mt_rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => (string)time(),
            'oauth_version'          => '1.0',
        ];

        $allParams = array_merge($oauthParams, ['format' => 'json', 'method' => $method], $extraParams);

        // Cast numeric values to strings for signing
        $signingParams = array_map('strval', $allParams);
        ksort($signingParams);
        $paramString = http_build_query($signingParams, '', '&', PHP_QUERY_RFC3986);
        $baseString  = 'POST'
            . '&' . rawurlencode($endpoint)
            . '&' . rawurlencode($paramString);

        $signingKey = rawurlencode($secret) . '&';
        $oauthParams['oauth_signature'] = base64_encode(
            hash_hmac('sha1', $baseString, $signingKey, true)
        );

        $postBody = array_merge($oauthParams, ['format' => 'json', 'method' => $method], $extraParams);

        $response = Http::asForm()
            ->withOptions(['verify' => true])
            ->post($endpoint, $postBody);

        return $response->json() ?? [];
    }
}
