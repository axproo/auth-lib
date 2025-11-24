<?php 

namespace Axproo\Auth\Pipelines;

class EmailPipeline extends BasePipeline
{
    protected array $steps = [];

    public function __construct() {
        parent::__construct($this->steps);
    }

    public function handle(array $payload) : array {
        return $this->setHandle($payload);
    }
}