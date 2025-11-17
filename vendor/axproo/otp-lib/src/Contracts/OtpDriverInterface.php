<?php 

namespace Axproo\Otp\Contracts;

interface OtpDriverInterface
{
    /**
     * Envoi le code OTP vers le destinataire (email/phone/secret)
     * @param string $receiver
     * @param string $code
     * @return bool
     */
    public function send(string $receiver, string $code, array $meta = []) : bool;

    /**
     * Vérifie le code (utile pour TOTP principalement, pour email/sms on vérifie via repository)
     * @param string $receiver
     * @param string $code
     * @return bool
     */
    public function verify(string $receiver, string $code) : bool;
}