<?php 

namespace Axproo\Auth\Exceptions;

use Exception;

class AuthException extends Exception
{
    protected array $payload = [];

    public function __construct(string $message = "", int $code = 401, array $payload = []) {
        parent::__construct($message, $code);
        $this->payload = $payload;
    }

    public function getPayload() : array {
        return $this->payload;
    }
}