<?php 

namespace Axproo\Auth\Libraries;

class CheckUserExists
{
    public function handle(array $data) : array {
        return ['check user' => true];
    }
}