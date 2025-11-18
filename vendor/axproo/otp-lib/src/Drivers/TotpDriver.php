<?php 

namespace Axproo\Otp\Drivers;

use Axproo\Otp\Contracts\OtpDriverInterface;
use OTPHP\TOTP; // composer require spomky-labs/otphp

class TotpDriver implements OtpDriverInterface
{
    public function send(string $receiver, string $code, array $meta = []): bool
    {
        // TOTP n'envoi rien. L'app génère le code avec le secret
        return true;
    }

    public function verify(string $receiver, string $code): bool
    {
        $totp = TOTP::create($receiver);
        return $totp->verify($code);
    }

    public function generate(string $receiver, $app) {
        $totp = TOTP::create();
        $totp->setLabel($receiver);
        $totp->setIssuer($app);

        return [
            'secret' => $totp->getSecret(),
            'uri' => $totp->getProvisioningUri()
        ];
    }
}