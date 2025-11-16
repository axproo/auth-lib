<?php 

namespace Axproo\Auth\Libraries;

class CheckUserExists
{

    public function __construct() {
        //
    }

    public function handle(array $data) : array {
        return ['check user' => $data];
    }
}