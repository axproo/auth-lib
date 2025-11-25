<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckEmailValidate;

class EmailPipeline extends BasePipeline
{
    protected array $steps = [
        CheckEmailValidate::class
    ];

    public function __construct() {
        parent::__construct($this->steps);
    }

    public function handle(array $payload) : array {
        return $this->setHandle($payload);
    }
}