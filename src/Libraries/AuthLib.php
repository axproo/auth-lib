<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use ReflectionClass;

class AuthLib
{
    protected array $steps = [
        CheckUserExists::class,
        CheckStatus::class,
        CheckPassword::class,
        CheckEmailVerified::class,
        CheckEmailValid::class,
        // CheckSessionAgent::class,
        // CheckLogoutRemote::class,
        // CheckTwoFactor::class,
        // CheckTwoFactorValidation::class,
        // FinalizeLogin::class
    ];

    // Etapes à exécuter obligatoirement
    protected array $alwaysRun = [
        CheckUserExists::class,
        CheckStatus::class,
    ];

    public function handle(array $payload): array
    {
        $data = $payload;

        // Récupération de la progression
        $currentStep = session()->get('auth.current_step') ?? 0;

        for ($i = $currentStep; $i < count($this->steps); $i++) { 
            $stepClass = $this->steps[$i];

            if (!class_exists($stepClass)) {
                throw new AuthException("Step class {$stepClass} not found", 500);
            }

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            if (!method_exists($step, 'handle')) {
                throw new AuthException("Step class {$stepClass} must implement handle()", 500);
            }

            // Si la step est "alwaysRun", on la réexécute même si on reprend un workflow
            if (!\in_array($stepClass, $this->alwaysRun)) {
                session()->set('auth.current_step', $i);
            }

            // Exécutioin de l'étape
            $data = $step->handle($data);

            if (!\is_array($data)) {
                throw new AuthException("Step {$stepClass} must return an array", 500);
            }

            // Si la step n'est pas validée -> arrêt immédiat
            if (!empty($data['stop_here']) && $data['stop_here'] === true) {
                // On reste sur la même étape pour la reprise
                session()->set('auth.current_step', $i);
                return $data;
            }
        }

        // Si toutes les étapes sont finies -> on supprime la progression
        session()->remove('auth.current_step');
        return $data;
    }
}
