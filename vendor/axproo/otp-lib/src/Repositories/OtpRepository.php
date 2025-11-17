<?php 

namespace Axproo\Otp\Repositories;

use Axproo\Otp\Models\OtpModel;
use CodeIgniter\I18n\Time;

class OtpRepository
{
    protected OtpModel $model;
    protected $useRedis = false;
    protected $redis;

    public function __construct(array $options = []) {
        $this->model = new OtpModel();

        if (!empty($options['redis'] && extension_loaded('redis'))) {
            $this->useRedis = true;
            $this->redis = $options['redis'];
        }
    }

    public function store(string $receiver, string $code, int $ttl = 300, string $channel = 'email') : bool {
        if ($this->useRedis && $this->redis) {
            return (bool) $this->redis->setex(
                $this->redisKey($channel, $receiver),
                $ttl,
                $code
            );
        }

        return (bool) $this->model->save([
            'receiver' => $receiver,
            'channel' => $channel,
            'code' => $code,
            'expires_at' => Time::now()->addSeconds($ttl)
        ]);
    }

    public function verify(string $receiver, string $code, string $channel = 'email') : bool {
        if ($this->useRedis && $this->redis) {
            $key = $this->redisKey($channel, $receiver);
            $stored = $this->redis->get($key);

            if (!$stored) return false;
            return hash_equals((string) $stored, (string) $code);
        }

        $row = $this->model->where([
                'receiver' => $receiver,
                'channel' => $channel
            ])->orderBy('created_at', 'DESC')->first();

        if (!$row) return false;
        if (strtotime($row->expires_at) < Time::now()) return false;
        if (!hash_equals($row->code, $code)) return false;

        $this->model->delete($row->id);
        return true;
    }

    protected function redisKey(string $channel, string $receiver) : string {
        return "otp:{$channel}:" . md5($receiver);
    }
}