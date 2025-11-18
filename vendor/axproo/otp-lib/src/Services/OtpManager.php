<?php 

namespace Axproo\Otp\Services;

use Axproo\Otp\Contracts\OtpDriverInterface;
use Axproo\Otp\Drivers\EmailDriver;
use Axproo\Otp\Drivers\SmsDriver;
use Axproo\Otp\Drivers\TotpDriver;
use Axproo\Otp\Helpers\OtpGenerator;
use Axproo\Otp\Repositories\OtpRepository;

class OtpManager
{
    protected OtpDriverInterface $driver;
    protected OtpRepository $repo;
    protected array $config;

    public function __construct(array $options = []) {
        // Config par défaut
        $this->config = $options['config'] ?? config('otp') ?? [
            'default' => 'email',
            'ttl' => 300
        ];

        $driverName = $options['driver'] ?? $this->config['default'] ?? 'email';
        $this->driver = $this->resolveDriver($driverName);
        $this->repo = new OtpRepository($options['repository'] ?? []);
    }

    protected function resolveDriver(string $name) : OtpDriverInterface {
        switch (strtolower($name)) {
            case 'sms': return new SmsDriver();
            case 'totp': return new TotpDriver();
            case 'email':
            default: return new EmailDriver();
        }
    }

    /**
     * Envoi un OTP et le stocke
     * 
     * @param string $receiver
     * @param array $meta
     * @param mixed $via
     * @return array{code: string|null, success: bool, ttl: mixed, via: string|array{message: string, success: bool, via: string}}
     */
    public function send(string $receiver, array $meta = [], ?string $via = null) : array {
        $driver = $via ? $this->resolveDriver($via) : $this->driver;
        $ttl = $meta['ttl'] ?? ($this->config['ttl'] ?? 300);

        // TOTP: si totp on ne génère pas de code (code dépend du secret)
        if ($driver instanceof TotpDriver) {
            // Dans le cas TOTP, receiver doit être le secret ou user secret
            return [
                'success' => true,
                'via' => 'totp',
                'message' => 'Use authenticator app'
            ];
        }

        $code = OtpGenerator::generate($meta['length'] ?? 6);

        // Store
        $this->repo->store($receiver, $code, $ttl, $meta['channel'] ?? 'email');

        // Send
        $sent = $driver->send($receiver, $code, array_merge($meta, ['expires_in' => $ttl]));
        
        return [
            'success' => (bool) $sent,
            'via' => get_class($driver),
            'ttl' => $ttl,
            'code' => (ENVIRONMENT === 'development' ? $code : null) // Option debug
        ];
    }

    public function verify(string $receiver, string $code, ?string $via = null, string $channel = 'email') : bool {
        $driver = $via ? $this->resolveDriver($via) : $this->driver;

        if ($driver instanceof TotpDriver) {
            return $driver->verify($receiver, $code);
        }
        return $this->repo->verify($receiver, $code, $channel);
    }

    public function generate(string $receiver) {
        $totp = new TotpDriver();
        return $totp->generate($receiver, 'totp');
    }
}