<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Public pages ──────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// ── Public: Patient food diary (token-based, no auth) ─────────────────────────
Route::get('diary/{token}', [\App\Http\Controllers\PatientFoodDiaryController::class, 'show'])
    ->name('food-diary.patient-show');
Route::post('diary/{token}', [\App\Http\Controllers\PatientFoodDiaryController::class, 'submit'])
    ->name('food-diary.patient-submit');

// ── Public: Patient consent (token-based, no auth) ────────────────────────────
Route::get('consent/{token}', [\App\Http\Controllers\PatientConsentController::class, 'show'])
    ->name('patient-consent.show');
Route::post('consent/{token}/accept', [\App\Http\Controllers\PatientConsentController::class, 'accept'])
    ->name('patient-consent.accept');
Route::post('consent/{token}/decline', [\App\Http\Controllers\PatientConsentController::class, 'decline'])
    ->name('patient-consent.decline');

// ── Public: Team invite acceptance ───────────────────────────────────────────
Route::get('invite/{token}', [InvitationController::class, 'accept'])->name('team.accept');

// ── Dashboard (auth + verified + admin approved) ─────────────────────────────

Route::get('/dashboard', function () {
    $userId = Auth::id();
    $startOfWeek = now()->startOfWeek();

    $patientCount     = \App\Models\Patient::where('user_id', $userId)->count();
    $newThisWeek      = \App\Models\Patient::where('user_id', $userId)
                            ->where('created_at', '>=', $startOfWeek)->count();
    $followUps        = \App\Models\PatientVisit::whereHas('patient', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        })->where('visited_at', '>=', now()->subDays(7))->count();
    $mealPlansCreated = \App\Models\MealPlannerWeek::where('user_id', $userId)->count();

    return view('dashboard', compact(
        'patientCount', 'newThisWeek', 'followUps', 'mealPlansCreated'
    ));
})->middleware(['auth', 'two-factor', 'verified'])->name('dashboard');

// ── Onboarding wizard (auth, exempt from onboarding middleware) ───────────────
Route::middleware(['auth'])->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::post('skip', [\App\Http\Controllers\OnboardingController::class, 'skip'])->name('skip');
    Route::get('{step?}', [\App\Http\Controllers\OnboardingController::class, 'show'])->name('show')->where('step', '[0-9]+');
    Route::post('{step}', [\App\Http\Controllers\OnboardingController::class, 'store'])->name('store')->where('step', '[0-9]+');
});

// ── Admin: verify dietician via signed URL (public — no auth required) ────────
Route::get('/admin/verify-dietician/{user}', [AdminController::class, 'verifyDietician'])
    ->name('admin.verify-dietician');

// ── Authenticated routes ───────────────────────────────────────────────────────

Route::middleware(['auth', 'two-factor'])->group(function () {

    // ── Profile ──────────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/letterhead', [ProfileController::class, 'updateLetterhead'])->name('profile.letterhead.update');
    Route::delete('/profile/letterhead', [ProfileController::class, 'removeLetterhead'])->name('profile.letterhead.remove');
    Route::get('/profile/letterhead/preview', [ProfileController::class, 'previewLetterhead'])->name('profile.letterhead.preview');

    // ── Notifications ────────────────────────────────────────────────────────
    Route::post('notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');

    Route::post('notifications/{id}/read', function (string $id) {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? route('food-diary.index'));
    })->name('notifications.read');

    // ── Subscription / Billing ───────────────────────────────────────────────
    Route::get('billing', [SubscriptionController::class, 'billing'])->name('billing');
    Route::get('subscribe/{slug}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('subscription/callback', [SubscriptionController::class, 'callback'])->name('subscription.callback');
    Route::post('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // ── Devices ──────────────────────────────────────────────────────────────
    Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::delete('devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::post('devices/revoke-others', [DeviceController::class, 'revokeOthers'])->name('devices.revoke-others');

    // ── Team / Invitations ────────────────────────────────────────────────────
    Route::get('team', [InvitationController::class, 'index'])->name('team.index');
    Route::post('team/invite', [InvitationController::class, 'store'])->name('team.invite');
    Route::delete('team/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('team.invitations.destroy');
    Route::delete('team/members/{member}', [InvitationController::class, 'removeMember'])->name('team.members.destroy');

    // ── Plan locked page (no plan gate – just auth) ──────────────────────────
    Route::get('plan-locked', function () {
        $requiredSlug    = request('required', session('required_plan', 'package_1'));
        $requiredPackage = \App\Models\PricingPackage::where('slug', $requiredSlug)->first();
        return view('plan-locked', compact('requiredPackage'));
    })->name('plan.locked');

    // ════════════════════════════════════════════════════════════════════════
    // CLINICAL FEATURES — require admin approval in addition to auth
    // ════════════════════════════════════════════════════════════════════════
    Route::middleware(['verified'])->group(function () {

    // FREE TIER — Patients (CRUD + calculations: BMI, ABW/IBW/AF, RMR/BMR)
    // All approved authenticated users may create/view/edit/delete patients and
    // see the Anthropometry section. Advanced features are locked in the view.
    Route::resource('patients', \App\Http\Controllers\PatientController::class);

    // Patient visit history (monitoring)
    Route::get('patients/{patient}/visits', [\App\Http\Controllers\PatientVisitController::class, 'index'])
        ->name('patients.visits.index');
    Route::get('patients/{patient}/visits/pdf', [\App\Http\Controllers\PatientVisitController::class, 'pdf'])
        ->name('patients.visits.pdf');
    Route::post('patients/{patient}/visits', [\App\Http\Controllers\PatientVisitController::class, 'store'])
        ->name('patients.visits.store');
    Route::delete('patients/{patient}/visits/{visit}', [\App\Http\Controllers\PatientVisitController::class, 'destroy'])
        ->name('patients.visits.destroy');

    // Clinical assessment fields (subjective assessment)
    Route::patch('patients/{patient}/clinical-assessment', [\App\Http\Controllers\PatientController::class, 'updateClinicalAssessment'])
        ->name('patients.clinical-assessment.update');

    // IBW BMI target selector
    Route::patch('patients/{patient}/ibw-target', [\App\Http\Controllers\PatientController::class, 'updateIbwTarget'])
        ->name('patients.ibw-target.update');

    Route::get('patients/{patient}/report', [\App\Http\Controllers\PatientController::class, 'report'])
        ->name('patients.report')
        ->middleware('plan:package_1');
    Route::get('patients/{patient}/report/pdf', [\App\Http\Controllers\PatientController::class, 'reportPdf'])
        ->name('patients.report.pdf')
        ->middleware('plan:package_1');
    Route::patch('patients/{patient}/weekly-reminder', [\App\Http\Controllers\PatientController::class, 'toggleWeeklyReminder'])
        ->name('patients.weekly-reminder.toggle')
        ->middleware('plan:package_1');
    Route::post('patients/{patient}/resend-consent', [\App\Http\Controllers\PatientController::class, 'resendConsent'])
        ->name('patients.resend-consent');
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])
        ->name('reports.index')
        ->middleware('plan:package_1');

    // ════════════════════════════════════════════════════════════════════════
    // PACKAGE 1+ FEATURES  (Free users are blocked from these actions)
    // Macronutrients, Exchange templates, Meal plan,
    // Meal Items library, Meal Planner, Grocery Lists
    // ════════════════════════════════════════════════════════════════════════
    Route::middleware('plan:package_1')->group(function () {

        // Macronutrients & exchange list
        Route::patch('patients/{patient}/macronutrients',
            [\App\Http\Controllers\PatientController::class, 'updateMacronutrients'])
            ->name('patients.macronutrients.update');

        Route::patch('patients/{patient}/exchange-items/{item}/nu',
            [\App\Http\Controllers\PatientController::class, 'updateExchangeItemNu'])
            ->name('patients.exchange-items.nu');

        Route::post('patients/{patient}/exchange-template',
            [\App\Http\Controllers\PatientController::class, 'createExchangeTemplate'])
            ->name('patients.exchange-template.create');

        Route::post('patients/{patient}/apply-preset',
            [\App\Http\Controllers\PatientController::class, 'applyPreset'])
            ->name('patients.apply-preset');

        Route::delete('patients/{patient}/preset',
            [\App\Http\Controllers\PatientController::class, 'clearPreset'])
            ->name('patients.clear-preset');

        Route::get('diet-presets/{preset}',
            [\App\Http\Controllers\PatientController::class, 'getPreset'])
            ->name('diet-presets.show');

        Route::patch('patients/{patient}/meal-plan',
            [\App\Http\Controllers\PatientController::class, 'saveMealPlan'])
            ->name('patients.meal-plan.save');

        // Recipes
        Route::get ('recipes/search',                                  [\App\Http\Controllers\RecipeController::class, 'search'])          ->name('recipes.search');
        Route::post('recipes/import-fatsecret',                        [\App\Http\Controllers\RecipeController::class, 'importFatSecret'])->name('recipes.import-fatsecret');
        Route::post('recipes/{recipe}/send',                           [\App\Http\Controllers\RecipeController::class, 'sendToPatient'])  ->name('recipes.send');
        Route::resource('recipes', \App\Http\Controllers\RecipeController::class)
            ->only(['index', 'show', 'destroy']);

        // Meal Items library
        Route::get ('meal-items/search',           [\App\Http\Controllers\MealItemController::class, 'search'])          ->name('meal-items.search');
        Route::post('meal-items/import-fatsecret', [\App\Http\Controllers\MealItemController::class, 'importFatSecret'])->name('meal-items.import-fatsecret');
        Route::post('meal-items/bulk',             [\App\Http\Controllers\MealItemController::class, 'bulkAction'])     ->name('meal-items.bulk');
        Route::resource('meal-items', \App\Http\Controllers\MealItemController::class)
            ->except(['show']);

        // Meal Planner  (URLs: /meal-planner, /meal-planner/{patient}/{mealPlanner})
        Route::get ('meal-planner',                                         [\App\Http\Controllers\MealPlannerController::class, 'index'])  ->name('meal-planner.index');
        Route::get ('meal-planner/create',                                  [\App\Http\Controllers\MealPlannerController::class, 'create']) ->name('meal-planner.create');
        Route::post('meal-planner',                                         [\App\Http\Controllers\MealPlannerController::class, 'store'])  ->name('meal-planner.store');
        Route::get ('meal-planner/food-search',                             [\App\Http\Controllers\MealPlannerController::class, 'foodSearch'])->name('meal-planner.food-search');
        Route::get ('meal-planner/presets',                                  [\App\Http\Controllers\MealPlannerController::class, 'presetList'])->name('meal-planner.presets');
        Route::get ('meal-planner/presets/{slug}',                           [\App\Http\Controllers\MealPlannerController::class, 'presetApply'])->name('meal-planner.preset-apply');
        Route::get ('meal-planner/{patient}/{mealPlanner}',                 [\App\Http\Controllers\MealPlannerController::class, 'show'])   ->name('meal-planner.show');
        Route::get ('meal-planner/{patient}/{mealPlanner}/pdf',              [\App\Http\Controllers\MealPlannerController::class, 'pdf'])        ->name('meal-planner.pdf');
        Route::get ('meal-planner/{patient}/{mealPlanner}/pdf/preview',     [\App\Http\Controllers\MealPlannerController::class, 'pdfPreview']) ->name('meal-planner.pdf-preview');
        Route::patch('meal-planner/{patient}/{mealPlanner}/entries',        [\App\Http\Controllers\MealPlannerController::class, 'saveEntries']) ->name('meal-planner.save-entries');
        Route::post ('meal-planner/{patient}/{mealPlanner}/repeat',         [\App\Http\Controllers\MealPlannerController::class, 'repeat'])      ->name('meal-planner.repeat');
        Route::delete('meal-planner/{patient}/{mealPlanner}',               [\App\Http\Controllers\MealPlannerController::class, 'destroy']) ->name('meal-planner.destroy');

        // Grocery Lists
        Route::resource('grocery-lists', \App\Http\Controllers\GroceryListController::class)
            ->except(['edit', 'update']);
        Route::post('grocery-lists/generate-from-plan/{week}',
            [\App\Http\Controllers\GroceryListController::class, 'generateFromPlan'])
            ->name('grocery-lists.generate-from-plan');
        Route::post('grocery-lists/{groceryList}/items',
            [\App\Http\Controllers\GroceryListController::class, 'addItem'])
            ->name('grocery-lists.items.add');
        Route::patch('grocery-lists/{groceryList}/items/{item}/toggle',
            [\App\Http\Controllers\GroceryListController::class, 'toggleItem'])
            ->name('grocery-lists.items.toggle');
        Route::delete('grocery-lists/{groceryList}/items/{item}',
            [\App\Http\Controllers\GroceryListController::class, 'removeItem'])
            ->name('grocery-lists.items.remove');
        Route::post('grocery-lists/{groceryList}/email',
            [\App\Http\Controllers\GroceryListController::class, 'sendEmail'])
            ->name('grocery-lists.email');
    });

    // ════════════════════════════════════════════════════════════════════
    // PACKAGE 2+ FEATURES  (Free and Package 1 users are blocked)
    // Pantry / Food diary
    // ════════════════════════════════════════════════════════════════════
    Route::middleware('plan:package_2')->group(function () {

        // Pantry / Food Diary
        Route::get('pantry', [\App\Http\Controllers\PantryController::class, 'index'])->name('pantry.index');
        Route::post('pantry', [\App\Http\Controllers\PantryController::class, 'store'])->name('pantry.store');
        Route::patch('pantry/{pantryItem}', [\App\Http\Controllers\PantryController::class, 'update'])->name('pantry.update');
        Route::delete('pantry/{pantryItem}', [\App\Http\Controllers\PantryController::class, 'destroy'])->name('pantry.destroy');

        // Daily Food Diary
        Route::get('food-diary', [\App\Http\Controllers\FoodDiaryController::class, 'index'])->name('food-diary.index');
        Route::get('food-diary/create', [\App\Http\Controllers\FoodDiaryController::class, 'create'])->name('food-diary.create');
        Route::post('food-diary', [\App\Http\Controllers\FoodDiaryController::class, 'store'])->name('food-diary.store');
        Route::post('food-diary/send-invite', [\App\Http\Controllers\FoodDiaryController::class, 'sendInvite'])->name('food-diary.send-invite');
        Route::get('food-diary/{foodDiary}', [\App\Http\Controllers\FoodDiaryController::class, 'show'])->name('food-diary.show');
        Route::get('food-diary/{foodDiary}/edit', [\App\Http\Controllers\FoodDiaryController::class, 'edit'])->name('food-diary.edit');
        Route::put('food-diary/{foodDiary}', [\App\Http\Controllers\FoodDiaryController::class, 'update'])->name('food-diary.update');
        Route::delete('food-diary/{foodDiary}', [\App\Http\Controllers\FoodDiaryController::class, 'destroy'])->name('food-diary.destroy');
        Route::get('food-diary/{foodDiary}/pdf', [\App\Http\Controllers\FoodDiaryController::class, 'pdf'])->name('food-diary.pdf');

        // Weekly Food Diary
        Route::get('food-diary-weekly',     [\App\Http\Controllers\WeeklyFoodDiaryController::class, 'show'])->name('food-diary.weekly');
        Route::get('food-diary-weekly/pdf', [\App\Http\Controllers\WeeklyFoodDiaryController::class, 'pdf']) ->name('food-diary.weekly.pdf');
    });

    // ════════════════════════════════════════════════════════════════════════
    // PACKAGE 3+ FEATURES  (Only top-tier users)
    // Enteral Nutrition Calculator
    // ════════════════════════════════════════════════════════════════════════
    Route::middleware('plan:package_3')->group(function () {
        Route::get ('patients/{patient}/enteral-nutrition',                 [\App\Http\Controllers\EnteralNutritionController::class, 'index'])  ->name('patients.enteral-nutrition.index');
        Route::post('patients/{patient}/enteral-nutrition',                 [\App\Http\Controllers\EnteralNutritionController::class, 'store'])  ->name('patients.enteral-nutrition.store');
        Route::get ('patients/{patient}/enteral-nutrition/pdf',             [\App\Http\Controllers\EnteralNutritionController::class, 'pdf'])    ->name('patients.enteral-nutrition.pdf');
        Route::delete('patients/{patient}/enteral-nutrition/{calculation}', [\App\Http\Controllers\EnteralNutritionController::class, 'destroy'])->name('patients.enteral-nutrition.destroy');
    });

        // ── Email template preview ────────────────────────────────────────────
        Route::get('email-preview',            [\App\Http\Controllers\EmailPreviewController::class, 'index'])->name('email-preview.index');
        Route::get('email-preview/{template}', [\App\Http\Controllers\EmailPreviewController::class, 'show'])->name('email-preview.show');

        // ── Email Templates (newsletter-style customisation) ─────────────────
        Route::prefix('email-templates')->name('email-templates.')->middleware('plan:package_1')->group(function () {
            Route::get ('/',              [\App\Http\Controllers\EmailTemplateController::class, 'index'])          ->name('index');
            Route::get ('/{type}/edit',   [\App\Http\Controllers\EmailTemplateController::class, 'edit'])           ->name('edit');
            Route::put ('/{type}',        [\App\Http\Controllers\EmailTemplateController::class, 'update'])         ->name('update');
            Route::get ('/{type}/preview',[\App\Http\Controllers\EmailTemplateController::class, 'preview'])        ->name('preview');
            Route::post('/{type}/test',   [\App\Http\Controllers\EmailTemplateController::class, 'sendTest'])       ->name('send-test');
            Route::patch('/schedule',     [\App\Http\Controllers\EmailTemplateController::class, 'updateSchedule']) ->name('schedule.update');
        });

    }); // end admin.approved group

}); // end auth group

// ── Paystack webhook — public, no auth, no CSRF ───────────────────────────────

Route::post('/webhooks/paystack', [PaystackWebhookController::class, 'handle'])
    ->name('webhooks.paystack')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
