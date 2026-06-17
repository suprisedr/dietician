<?php

namespace App\Notifications;

use App\Models\FoodDiary;
use Illuminate\Notifications\Notification;

class FoodDiarySubmitted extends Notification
{
    public function __construct(public readonly FoodDiary $diary) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $patient = $this->diary->patient;

        return [
            'food_diary_id' => $this->diary->id,
            'patient_id'    => $patient->id,
            'patient_name'  => trim($patient->name . ' ' . ($patient->surname ?? '')),
            'diary_date'    => $this->diary->diary_date->format('d M Y'),
            'rating'        => $this->diary->rating,
            'url'           => route('food-diary.show', $this->diary->id),
        ];
    }
}
