<?php

namespace App\Services;

use App\Models\Passkey;
use App\Models\User;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\CredentialRecord;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class WebAuthnService
{
    private function serializer()
    {
        $mgr = new AttestationStatementSupportManager([new NoneAttestationStatementSupport()]);
        return (new WebauthnSerializerFactory($mgr))->create();
    }

    private function factory(): CeremonyStepManagerFactory
    {
        $factory = new CeremonyStepManagerFactory();
        $factory->setAllowedOrigins([$this->origin()]);
        return $factory;
    }

    private function rpId(): string
    {
        return parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    }

    private function origin(): string
    {
        // Strip trailing slash; keep port if non-standard
        return rtrim(config('app.url'), '/');
    }

    // ── Registration ──────────────────────────────────────────────────────────

    public function registrationOptions(User $user): PublicKeyCredentialCreationOptions
    {
        $rp   = PublicKeyCredentialRpEntity::create(name: config('app.name'), id: $this->rpId());
        $u    = PublicKeyCredentialUserEntity::create(
            name: $user->email,
            id: (string) $user->id,
            displayName: $user->name,
        );

        $exclude = $user->passkeys->map(
            fn ($pk) => PublicKeyCredentialDescriptor::create('public-key', base64_decode($pk->credential_id))
        )->all();

        return new PublicKeyCredentialCreationOptions(
            rp: $rp,
            user: $u,
            challenge: random_bytes(32),
            pubKeyCredParams: [
                PublicKeyCredentialParameters::create('public-key', -7),   // ES256
                PublicKeyCredentialParameters::create('public-key', -257), // RS256
            ],
            authenticatorSelection: AuthenticatorSelectionCriteria::create(
                residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_PREFERRED,
                userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            ),
            attestation: PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            excludeCredentials: $exclude,
            timeout: 60_000,
        );
    }

    public function processRegistration(
        User $user,
        string $responseJson,
        PublicKeyCredentialCreationOptions $options,
        string $name,
    ): Passkey {
        $s          = $this->serializer();
        $credential = $s->deserialize($responseJson, PublicKeyCredential::class, 'json');

        if (! $credential->response instanceof AuthenticatorAttestationResponse) {
            throw new \RuntimeException('Invalid WebAuthn response type for registration.');
        }

        $record = AuthenticatorAttestationResponseValidator::create($this->factory()->creationCeremony())
            ->check($credential->response, $options, $this->rpId());

        return $user->passkeys()->create([
            'name'              => $name,
            'credential_id'     => base64_encode($record->publicKeyCredentialId),
            'credential_record' => $s->serialize($record, 'json'),
            'counter'           => $record->counter,
        ]);
    }

    // ── Authentication ────────────────────────────────────────────────────────

    public function authenticationOptions(User $user): PublicKeyCredentialRequestOptions
    {
        $allow = $user->passkeys->map(
            fn ($pk) => PublicKeyCredentialDescriptor::create('public-key', base64_decode($pk->credential_id))
        )->all();

        return PublicKeyCredentialRequestOptions::create(
            challenge: random_bytes(32),
            rpId: $this->rpId(),
            allowCredentials: $allow,
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            timeout: 60_000,
        );
    }

    public function processAuthentication(
        User $user,
        string $responseJson,
        PublicKeyCredentialRequestOptions $options,
    ): bool {
        $s          = $this->serializer();
        $credential = $s->deserialize($responseJson, PublicKeyCredential::class, 'json');

        if (! $credential->response instanceof AuthenticatorAssertionResponse) {
            throw new \RuntimeException('Invalid WebAuthn response type for authentication.');
        }

        $credentialId = base64_encode($credential->rawId);
        $passkey      = $user->passkeys()->where('credential_id', $credentialId)->first();

        if (! $passkey) {
            return false;
        }

        $stored  = $s->deserialize($passkey->credential_record, CredentialRecord::class, 'json');
        $updated = AuthenticatorAssertionResponseValidator::create($this->factory()->requestCeremony())
            ->check($stored, $credential->response, $options, $this->rpId(), (string) $user->id);

        $passkey->update(['counter' => $updated->counter, 'last_used_at' => now()]);

        return true;
    }
}
