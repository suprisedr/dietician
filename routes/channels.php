<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Private channel for a single patient record.
 * Only the dietician who owns the patient may subscribe.
 */
Broadcast::channel('patient.{patientId}', function ($user, $patientId) {
    return Patient::where('id', $patientId)
        ->where('user_id', $user->id)
        ->exists();
});
