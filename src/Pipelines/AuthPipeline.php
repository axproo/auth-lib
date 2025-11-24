<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckUserExists;

class AuthPipeline
{
    protected array $step = [
        CheckUserExists::class
    ];

    public function handle(array $payload) : array {
        $data = $payload;

        // Récupération de la progression
        $currentStep = session()->get('current_step') ?? 0;

        $data['current_step'] = $currentStep;
        return $data;
    }
}