<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckUserExists;

class AuthPipeline
{
    protected array $step = [
        CheckUserExists::class
    ];

    public function handle(array $payload) : array {
        return $payload;
    }
}