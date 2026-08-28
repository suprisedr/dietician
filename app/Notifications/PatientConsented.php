<?php

namespace App\Notifications;

use App\Models\Patient;
use Illuminate\Notifications\Notification;

class PatientConsented extends Notification
{
    public function __construct(public readonly Patient $patient) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'patient_id'   => $this->patient->id,
            'patient_name' => $this->patient->full_name,
            'consented_at' => ($this->patient->consented_at ?? now())->format('d M Y \a\t H:i'),
            'url'          => route('patients.show', $this->patient->id),
        ];
    }
}
