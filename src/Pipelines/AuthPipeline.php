<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Exceptions\AuthException;
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

        for ($i= $currentStep; $i < \count($this->step); $i++) { 
            $stepClass = $this->step[$i];

            if (!class_exists($stepClass)) {
                throw new AuthException(lang('Steps.not_found', ['step' => $stepClass]), 500);
            }
        }

        $data['current_step'] = $currentStep;
        return $data;
    }
}