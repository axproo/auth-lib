<?php 

namespace Axproo\Auth\Steps;

class CheckEmailValidate extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        return $data;
    }
}