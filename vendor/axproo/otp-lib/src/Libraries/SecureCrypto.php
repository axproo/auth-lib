<?php 

namespace Axproo\Otp\Libraries;

class SecureCrypto
{
    private string $key;

    public function __construct() {
        $rawKey = env('encryption.key');

        if (!$rawKey) {
            throw new \RuntimeException(lang('Encryption.key.not_defined'));
        }

        $this->key = base64_decode($rawKey, true);

        if ($this->key === false || \strlen($this->key) !== 32) {
            throw new \RuntimeException(lang('Encryption.key.invalid'));
        }
    }

    public function encrypt(string $plain) : string {
        $iv = random_bytes(12);

        $ciphertext = openssl_encrypt(
            $plain,
            'aes-256-gcm',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        if ($ciphertext === false) {
            throw new \RuntimeException(lang('Encryption.key.failed'));
        }
        return base64_encode($iv . $tag . $ciphertext);
    }

    public function decrypt(string $ciphertext) : string {
        $data = base64_decode($ciphertext);

        $iv     = substr($data, 0, 12);
        $tag    = substr($data, 12, 16);
        $cipher = substr($data, 28);

        $plain = openssl_decrypt(
            $cipher,
            'aes-256-gcm',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($plain === false) {
            throw new \RuntimeException(lang('Encryption.key.failed'));
        }
        return $plain;
    }
}