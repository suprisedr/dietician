<?php

namespace App\Http\Controllers;

use App\Models\FoodDiary;
use App\Models\GroceryList;
use App\Models\GroceryListItem;
use App\Models\MealPlannerWeek;
use App\Models\Patient;
use App\Models\Recipe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    // ── Index: list all previewable templates ─────────────────────────────────
    public function index()
    {
        $templates = [
            [
                'key'         => 'account-unlocked',
                'label'       => 'Account Unlocked',
                'description' => 'Sent to a dietician when their account is activated by an admin.',
                'icon'        => '🔓',
            ],
            [
                'key'         => 'admin-new-dietician',
                'label'       => 'Admin — New Dietician Registration',
                'description' => 'Sent to the admin when a new dietician registers, with a verification link.',
                'icon'        => '🛡️',
            ],
            [
                'key'         => 'patient-consent',
                'label'       => 'Patient Consent Request',
                'description' => 'Sent to a patient asking them to consent to clinical data management.',
                'icon'        => '📋',
            ],
            [
                'key'         => 'food-diary-invite',
                'label'       => 'Food Diary Invite',
                'description' => 'Sent to a patient with a link to fill in their daily food diary.',
                'icon'        => '🍽️',
            ],
            [
                'key'         => 'grocery-list',
                'label'       => 'Grocery List',
                'description' => 'Sent to a patient containing their weekly grocery list.',
                'icon'        => '🛒',
            ],
            [
                'key'         => 'recipe',
                'label'       => 'Recipe Recommendation',
                'description' => 'Sent to a patient sharing a recipe recommended by their dietician.',
                'icon'        => '👨‍🍳',
            ],
            [
                'key'         => 'weekly-meal-plan-reminder',
                'label'       => 'Weekly Meal Plan Reminder (Legacy)',
                'description' => 'Original weekly reminder email containing the patient\'s full meal plan summary.',
                'icon'        => '📅',
            ],
            [
                'key'         => 'motivational-reminder',
                'label'       => 'Motivational Reminder — Week A',
                'description' => 'Alternating motivational email (odd ISO weeks) — "Fuel Your Week" green theme.',
                'icon'        => '🥗',
                'params'      => ['variant' => 1],
            ],
            [
                'key'         => 'motivational-reminder',
                'label'       => 'Motivational Reminder — Week B',
                'description' => 'Alternating motivational email (even ISO weeks) — "Stay Strong" blue theme.',
                'icon'        => '💧',
                'params'      => ['variant' => 2],
            ],
        ];

        return view('email-preview.index', compact('templates'));
    }

    // ── Preview a single template ─────────────────────────────────────────────
    public function show(Request $request, string $template)
    {
        $variant = (int) $request->input('variant', 1);

        // Build fake data objects — no DB queries needed
        $dietician = $this->fakeDietician();
        $patient   = $this->fakePatient($dietician);

        return match ($template) {
            'account-unlocked' => view('emails.account-unlocked', [
                'dietician' => $dietician,
            ]),

            'admin-new-dietician' => view('emails.admin-new-dietician', [
                'dietician'      => $dietician,
                'verifyUrl'      => '#preview-verify-url',
                'hpcsaLookupUrl' => 'https://isystems.hpcsa.co.za/iregister/',
            ]),

            'patient-consent' => view('emails.patient-consent', [
                'patient'     => $patient,
                'dietician'   => $dietician,
                'consentLink' => '#preview-consent-url',
            ]),

            'food-diary-invite' => view('emails.food-diary-invite', [
                'diary'     => $this->fakeDiary($patient),
                'dietician' => $dietician,
                'link'      => '#preview-diary-url',
            ]),

            'grocery-list' => view('emails.grocery-list', [
                'groceryList' => $this->fakeGroceryList($patient),
                'byCategory'  => $this->fakeGroceryItems(),
            ]),

            'recipe' => view('emails.recipe', [
                'recipe'    => $this->fakeRecipe(),
                'patient'   => $patient,
                'note'      => 'Hi Sarah, I thought you\'d love this light Mediterranean salad. Perfect as a lunch option for your meal plan this week!',
                'dietician' => $dietician,
            ]),

            'weekly-meal-plan-reminder' => view('emails.weekly-meal-plan-reminder', [
                'patient'   => $patient,
                'dietician' => $dietician,
                'week'      => $this->fakeMealPlannerWeek($patient),
            ]),

            'motivational-reminder' => view('emails.motivational-reminder', [
                'patient'   => $patient,
                'dietician' => $dietician,
                'variant'   => in_array($variant, [1, 2]) ? $variant : 1,
            ]),

            default => abort(404, 'Email template not found.'),
        };
    }

    // ── Fake data helpers ─────────────────────────────────────────────────────

    private function fakeDietician(): User
    {
        $u = new User();
        $u->id               = 0;
        $u->name             = auth()->user()->name ?? 'Dr. Jane Smith';
        $u->email            = auth()->user()->email ?? 'jane.smith@nutritionpro.co.za';
        $u->dietician_number = auth()->user()->dietician_number ?? 'RD-12345';
        return $u;
    }

    private function fakePatient(User $dietician): Patient
    {
        $p = new Patient();
        $p->id                   = 0;
        $p->user_id              = $dietician->id;
        $p->name                 = 'Sarah';
        $p->surname              = 'Johnson';
        $p->email                = 'sarah.johnson@example.com';
        $p->gender               = 'female';
        $p->age                  = 34;
        $p->weight               = 72.5;
        $p->height               = 165;
        $p->date_of_birth        = Carbon::parse('1992-03-15');
        $p->reason_for_assessment = 'Weight management & improved energy levels';
        $p->medical_history      = 'Type 2 diabetes, mild hypertension';
        $p->medications          = 'Metformin 500mg';
        $p->allergies            = 'Peanuts';
        $p->dietary_history      = 'Predominantly Western diet, eats out 3–4 times/week';
        $p->appetite             = 'fair';
        $p->consent_token        = 'preview-token-abc123';
        return $p;
    }

    private function fakeDiary(Patient $patient): FoodDiary
    {
        $d = new FoodDiary();
        $d->id            = 1;
        $d->patient_id    = 0;
        $d->patient_name  = $patient->full_name;
        $d->patient_email = $patient->email;
        $d->patient_token = 'preview-diary-token';
        $d->date          = Carbon::today();
        $d->setRelation('patient', $patient);
        return $d;
    }

    private function fakeGroceryList(Patient $patient): GroceryList
    {
        $gl = new GroceryList();
        $gl->id         = 1;
        $gl->name       = 'Week of ' . now()->startOfWeek()->format('d M Y');
        $gl->patient_id = 0;
        $gl->user_id    = 0;
        // Attach fake items collection
        $items = collect($this->fakeGroceryItems())->flatten();
        $gl->setRelation('items', $items);
        return $gl;
    }

    private function fakeGroceryItems(): \Illuminate\Support\Collection
    {
        $raw = [
            'produce' => [
                ['name' => 'Baby spinach (200g)', 'qty' => '1 bag', 'checked' => false, 'category' => 'produce'],
                ['name' => 'Broccoli',             'qty' => '2 heads', 'checked' => false, 'category' => 'produce'],
                ['name' => 'Cherry tomatoes',      'qty' => '1 punnet', 'checked' => true, 'category' => 'produce'],
                ['name' => 'Avocados',             'qty' => '3', 'checked' => false, 'category' => 'produce'],
            ],
            'meat' => [
                ['name' => 'Chicken breast (skinless)', 'qty' => '1 kg', 'checked' => false, 'category' => 'meat'],
                ['name' => 'Salmon fillets',            'qty' => '500g', 'checked' => false, 'category' => 'meat'],
            ],
            'dairy' => [
                ['name' => 'Low-fat Greek yoghurt', 'qty' => '500g', 'checked' => false, 'category' => 'dairy'],
                ['name' => 'Skim milk',              'qty' => '2 L',  'checked' => false, 'category' => 'dairy'],
            ],
            'pantry' => [
                ['name' => 'Brown rice',       'qty' => '1 kg',  'checked' => false, 'category' => 'pantry'],
                ['name' => 'Olive oil (extra virgin)', 'qty' => '1 bottle', 'checked' => false, 'category' => 'pantry'],
                ['name' => 'Rolled oats',      'qty' => '500g',  'checked' => true, 'category' => 'pantry'],
            ],
        ];

        return collect($raw)->map(function (array $items, string $cat) {
            return collect($items)->map(function (array $item) {
                $gi           = new GroceryListItem();
                $gi->name     = $item['name'];
                $gi->qty      = $item['qty'];
                $gi->checked  = $item['checked'];
                $gi->category = $item['category'];
                return $gi;
            });
        });
    }

    private function fakeRecipe(): Recipe
    {
        $r = new Recipe();
        $r->id          = 1;
        $r->name        = 'Mediterranean Chickpea Salad';
        $r->description = 'A light, refreshing salad packed with protein and healthy fats. Great as a standalone lunch or as a side dish.';
        $r->servings    = 2;
        $r->prep_time   = 10;
        $r->cook_time   = 0;
        $r->calories    = 320;
        $r->protein     = 14;
        $r->carbs       = 28;
        $r->fat         = 16;
        $r->ingredients = "1 can chickpeas (drained)\n1 cucumber (diced)\n200g cherry tomatoes (halved)\n½ red onion (finely sliced)\n50g feta cheese (crumbled)\n2 tbsp olive oil\nJuice of 1 lemon\nFresh parsley\nSalt & pepper to taste";
        $r->instructions = "1. Drain and rinse the chickpeas.\n2. Combine all vegetables in a large bowl.\n3. Add chickpeas and feta.\n4. Drizzle with olive oil and lemon juice.\n5. Season, toss, and garnish with parsley.";
        return $r;
    }

    private function fakeMealPlannerWeek(Patient $patient): MealPlannerWeek
    {
        $w = new MealPlannerWeek();
        $w->id         = 1;
        $w->patient_id = 0;
        $w->user_id    = 0;
        $w->week_start = Carbon::now()->startOfWeek();
        $w->label      = 'Preview Week';

        // Build a few fake entries
        $entries = collect([
            $this->fakeEntry(0, 'breakfast', 'Oats with banana & skim milk'),
            $this->fakeEntry(0, 'lunch',     'Grilled chicken salad'),
            $this->fakeEntry(0, 'dinner',    'Baked salmon with steamed broccoli'),
            $this->fakeEntry(1, 'breakfast', 'Greek yoghurt with berries'),
            $this->fakeEntry(1, 'lunch',     'Brown rice & lentil soup'),
            $this->fakeEntry(1, 'dinner',    'Stir-fry vegetables with tofu'),
            $this->fakeEntry(2, 'breakfast', 'Scrambled eggs on wholewheat toast'),
            $this->fakeEntry(2, 'snack1',    'Apple & 10 almonds'),
        ]);

        $w->setRelation('entries', $entries);
        return $w;
    }

    private function fakeEntry(int $day, string $slot, string $text): \App\Models\MealPlannerEntry
    {
        $e               = new \App\Models\MealPlannerEntry();
        $e->day_of_week  = $day;
        $e->meal_slot    = $slot;
        $e->meal_text    = $text;
        $e->meal_item_id = null;
        $e->qty          = 1;
        $e->sort_order   = 0;
        return $e;
    }
}
