<?php 

namespace Axproo\Otp\Entities;

use CodeIgniter\Entity\Entity;

class UserEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'two_factor_enabled' => null,
        'totp_secret' => null,
        'totp_uri' => null,
    ];
    protected $datamap = [];
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $casts = [
        'id' => 'integer',
        'two_factor_enable' => 'boolean',
        'totp_secret' => '?string',
        'totp_uri' => '?string'
    ];
}