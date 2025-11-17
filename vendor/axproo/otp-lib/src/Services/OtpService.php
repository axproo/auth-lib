<?php 

namespace Axproo\Otp\Services;

use Axproo\Otp\Config\Otp as OtpConfig;

class OtpService
{
    protected OtpManager $manager;

    public function __construct(array $options = []) {
        $config = config(OtpConfig::class);

        $options['redis'] ??= $config->redis;
        $this->manager = new OtpManager($options);
    }

    public function sendForEmail(string $email, array $meta = []) {
        return $this->manager->send($email, array_merge($meta, ['channel' => 'email']), 'email');
    }

    public function sendForSms(string $phone, array $meta = []) {
        return $this->manager->send($phone, array_merge($meta, ['channel' => 'sms']), 'sms');
    }

    public function verify(string $receiver, string $code, string $channel = 'email') {
        return $this->manager->verify($receiver, $code, null, $channel);
    }

    public function verifyTotp(string $secret, string $code) {
        return $this->manager->verify($secret, $code, 'totp');
    }
}