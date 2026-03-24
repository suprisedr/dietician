<?php

namespace App\Events;

use App\Models\Patient;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired whenever a patient's computed figures change (e.g. IBW target toggle,
 * macronutrient save). The payload contains every value the patient show page
 * needs to update its DOM live — no page reload required.
 */
class PatientUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $payload;

    public function __construct(Patient $patient)
    {
        $patient->loadMissing('macronutrients');

        $teeKj   = $patient->tee ?? 0;
        $teeKcal = $teeKj ? round($teeKj / 4.184) : null;
        $rmrKcal = $patient->bmr;
        $rmrKj   = $rmrKcal ? round($rmrKcal * 4.184) : null;

        $macros = $patient->macronutrients->map(function ($m) use ($teeKj) {
            $kj      = ($m->selected_percentage / 100) * $teeKj;
            $divisor = in_array($m->type, ['fat', 'fats']) ? 38 : 17;
            $grams   = $kj > 0 ? round($kj / $divisor) : 0;
            return [
                'id'    => $m->id,
                'type'  => $m->type,
                'kj'    => round($kj, 1),
                'grams' => $grams,
            ];
        })->values()->all();

        $this->payload = [
            'patient_id'     => $patient->id,
            'ibw_bmi_target' => $patient->ibw_bmi_target,
            'use_ibw_weight' => (bool) $patient->use_ibw_weight,
            'ibw'            => $patient->ibw,
            'abw'            => $patient->abw,
            'weight_for_bmr' => $patient->weight_for_bmr,
            'rmr_kcal'       => $rmrKcal ? round($rmrKcal) : null,
            'rmr_kj'         => $rmrKj,
            'tee_kj'         => $teeKj ? round($teeKj) : null,
            'tee_kcal'       => $teeKcal,
            'macros'         => $macros,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('patient.' . $this->payload['patient_id']),
        ];
    }

    public function broadcastAs(): string
    {
        return 'patient.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
