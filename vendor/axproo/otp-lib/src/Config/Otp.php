<?php 

namespace Axproo\Otp\Config;

use Axproo\Otp\Drivers\EmailDriver;
use Axproo\Otp\Drivers\SmsDriver;
use Axproo\Otp\Drivers\TotpDriver;

class Otp
{
    public string $default = 'email';
    public int $ttl = 300; // 5 min
    public array $drivers = [
        'email' => EmailDriver::class,
        'sms'   => SmsDriver::class,
        'totp'  => TotpDriver::class
    ];

    // Option Redis
    public array $redis = [
        'enabled' => false,
        'instance' => null // example: service('redis')
    ];
}