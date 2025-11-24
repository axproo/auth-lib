<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Steps\CheckUserExists;
use ReflectionClass;

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

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            if (!method_exists($step, 'handle')) {
                throw new AuthException(lang('Steps.not_implement', [
                    'step' => $stepClass,
                    'method' => 'handle()'
                ]), 500);
            }

            // Exécution de l'étape
            $data = $step->handle($data);

            if (!\is_array($data)) {
                throw new AuthException(lang('Steps.not_array', [
                    'step' => $stepClass
                ]), 500);
            }

            // Si la step n'est pas valide -> arrêt immédiat
            if (!empty($data['stop_here']) && $data['stop_here'] === true) {
                session()->set('current_step', $i);
                return $data;
            }
        }
        
        // Si toutes les étapes sont finalisées -> on supprime la progression
        session()->remove('current_step');
        $data['current_step'] = session()->get('current_step');
        return $data;
    }
}