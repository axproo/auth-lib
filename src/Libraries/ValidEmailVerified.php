<?php 

namespace Axproo\Auth\Libraries;

class ValidEmailVerified
{
    public function handle(array $data) : array {
        $data['hello'] = 'test';
        return $data;
    }
}