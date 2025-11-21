<?php 

namespace Axproo\Otp\Drivers;

use Axproo\Otp\Contracts\OtpDriverInterface;
use Axproo\Otp\Libraries\QrCodeManager;
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
        $qrcode = new QrCodeManager();
        return $qrcode->verify($receiver, $code);
        // $totp = TOTP::create($receiver);
        // return $totp->verify($code);
    }

    public function generate(string $receiver, $app) {
        $qrcode = new QrCodeManager();
        return $qrcode->initialize($receiver, $app);
    }
}