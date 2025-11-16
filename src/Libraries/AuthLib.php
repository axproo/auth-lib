<?php 

namespace Axproo\Auth\Libraries;

class AuthLib
{
    protected array $steps = [];

    public function handle(array $payload) : array {
        return [
            'pipeline' => true
        ];
    }
}