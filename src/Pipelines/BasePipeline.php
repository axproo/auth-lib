<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Exceptions\AuthException;
use ReflectionClass;

abstract class BasePipeline
{
    protected array $step = [];

    public function __construct(array $step = []) {
        $this->step = $step;
    }
    
    protected function setHandle(array $payload) : array {
        $data = $payload;

        // Récupération de la progression
        $currentStep = session()->get('current_step') ?? 0;

        for ($i= $currentStep; $i < \count($this->step); $i++) { 
            $stepClass = $this->step[$i];

            if (!class_exists($stepClass)) {
                throw new AuthException(lang('Steps.not_found', ['step' => $stepClass]), 500, [
                    'stop_here' => true
                ]);
            }

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            if (!method_exists($step, 'handle')) {
                throw new AuthException(lang('Steps.not_implement', [
                    'step' => $stepClass,
                    'method' => 'handle()'
                ]), 500, ['stop_here' => true]);
            }

            // Exécution de l'étape
            $data = $step->handle($data);

            if (!\is_array($data)) {
                throw new AuthException(lang('Steps.not_array', [
                    'step' => $stepClass
                ]), 500, ['stop_here' => true]);
            }

            // Si la step n'est pas valide -> arrêt immédiat
            if (!empty($data['stop_here']) && $data['stop_here'] === true) {
                session()->set('current_step', $i);
                return $data;
            }

            // Etapé suivante
            session()->set('current_step', $i + 1);
        }
        
        // Si toutes les étapes sont finalisées -> on supprime la progression
        session()->remove('current_step');
        $data['current_step'] = null;
        
        return $data;
    }
}