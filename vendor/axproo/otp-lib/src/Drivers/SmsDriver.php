<?php 

namespace Axproo\Otp\Drivers;

use Axproo\Otp\Contracts\OtpDriverInterface;

class SmsDriver implements OtpDriverInterface
{
    /**
     * Envoi du code OTP par SMS
     *
     * @param string $receiver
     * @param string $code
     * @param array $meta
     * @return boolean
     */
    public function send(string $receiver, string $code, array $meta = []): bool
    {
        // TODO: intégrer Twilio / Vonage / Digital Virgo / Fournisseur Local
        return false;
    }

    public function verify(string $receiver, string $code): bool
    {
        return true;
    }
}