<?php 

namespace Axproo\Auth\Pipelines;

class EmailPipeline
{
    protected array $step = [];

    public function handle(array $payload) : array {
        $data = $payload;
        
        return $data;
    }
}