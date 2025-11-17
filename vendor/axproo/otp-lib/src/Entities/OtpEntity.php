<?php 
namespace Axproo\Otp\Entities;

use CodeIgniter\Entity\Entity;

class OtpEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'code' => null,
        'receiver' => null,
        'channel' => null,
        'purpose' => null,
        'target' => "email",
        'is_used' => null,
        'expires_at' => null,
        'created_at' => null,
        'updated_at' => null
    ];
    protected $datamap = [];
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];
}