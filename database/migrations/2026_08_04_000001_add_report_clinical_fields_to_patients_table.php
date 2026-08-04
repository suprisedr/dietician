<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Patient identification
            $table->string('folder_number', 100)->nullable()->after('id_number');
            $table->string('ward_clinic', 255)->nullable()->after('folder_number');
            $table->string('contact_number', 50)->nullable()->after('ward_clinic');
            $table->string('medical_diagnosis', 500)->nullable()->after('contact_number');

            // Anthropometrics
            $table->decimal('muac', 5, 1)->nullable()->after('height');
            $table->decimal('weight_history_3m', 5, 1)->nullable()->after('muac');
            $table->string('nutrition_risk_must', 20)->nullable()->after('weight_history_3m');

            // Biochemistry
            $table->string('bp', 20)->nullable()->after('nutrition_risk_must');
            $table->decimal('blood_glucose', 5, 1)->nullable()->after('bp');
            $table->decimal('hba1c', 4, 1)->nullable()->after('blood_glucose');
            $table->decimal('hb', 5, 1)->nullable()->after('hba1c');
            $table->decimal('albumin', 5, 1)->nullable()->after('hb');
            $table->decimal('creatinine', 6, 1)->nullable()->after('albumin');
            $table->decimal('urea', 5, 1)->nullable()->after('creatinine');
            $table->decimal('sodium_na', 5, 1)->nullable()->after('urea');
            $table->decimal('potassium_k', 4, 1)->nullable()->after('sodium_na');
            $table->decimal('cholesterol', 4, 1)->nullable()->after('potassium_k');

            // Clinical findings (JSON array of selected findings)
            $table->json('clinical_findings')->nullable()->after('cholesterol');
            $table->string('clinical_findings_other', 255)->nullable()->after('clinical_findings');

            // Additional subjective fields
            $table->text('gi_symptoms')->nullable()->after('appetite');
            $table->text('lifestyle')->nullable()->after('gi_symptoms');

            // Nutrition Impact Symptoms (JSON array)
            $table->json('nutrition_impact_symptoms')->nullable()->after('lifestyle');

            // PES Statement
            $table->text('pes_problem')->nullable()->after('nutrition_impact_symptoms');
            $table->text('pes_etiology')->nullable()->after('pes_problem');
            $table->text('pes_signs_symptoms')->nullable()->after('pes_etiology');
            $table->text('nutrition_diagnosis_priority_1')->nullable()->after('pes_signs_symptoms');
            $table->text('nutrition_diagnosis_priority_2')->nullable()->after('nutrition_diagnosis_priority_1');
            $table->text('nutrition_diagnosis_priority_3')->nullable()->after('nutrition_diagnosis_priority_2');

            // Nutrition Prescription (JSON array of selected prescriptions)
            $table->json('nutrition_prescription')->nullable()->after('nutrition_diagnosis_priority_3');

            // Intervention Details
            $table->text('nutrition_intervention')->nullable()->after('nutrition_prescription');
            $table->text('meal_plan_details')->nullable()->after('nutrition_intervention');
            $table->text('oral_supplements')->nullable()->after('meal_plan_details');
            $table->text('nutrition_education')->nullable()->after('oral_supplements');
            $table->text('intervention_goals')->nullable()->after('nutrition_education');
            $table->text('monitoring_plan')->nullable()->after('intervention_goals');
            $table->text('follow_up_plan')->nullable()->after('monitoring_plan');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'folder_number', 'ward_clinic', 'contact_number', 'medical_diagnosis',
                'muac', 'weight_history_3m', 'nutrition_risk_must',
                'bp', 'blood_glucose', 'hba1c', 'hb', 'albumin', 'creatinine',
                'urea', 'sodium_na', 'potassium_k', 'cholesterol',
                'clinical_findings', 'clinical_findings_other',
                'gi_symptoms', 'lifestyle', 'nutrition_impact_symptoms',
                'pes_problem', 'pes_etiology', 'pes_signs_symptoms',
                'nutrition_diagnosis_priority_1', 'nutrition_diagnosis_priority_2', 'nutrition_diagnosis_priority_3',
                'nutrition_prescription',
                'nutrition_intervention', 'meal_plan_details', 'oral_supplements',
                'nutrition_education', 'intervention_goals', 'monitoring_plan', 'follow_up_plan',
            ]);
        });
    }
};
