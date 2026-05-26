<x-mail::message>
# Verification code

Hi {{ $user->name }},

Use the code below to complete your sign-in. It expires in **10 minutes**.

<x-mail::panel>
<div style="font-size:2rem;font-weight:800;letter-spacing:.35em;text-align:center">{{ $code }}</div>
</x-mail::panel>

If you did not request this code, please ignore this email or contact support if you are concerned.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
