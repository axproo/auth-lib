<?php 
namespace Axproo\Auth\Entities;

use CodeIgniter\Entity\Entity;

class UserEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'password' => null,
        'role_id' => null,
        'user_type' => null,
        'owner_id' => null,
        'owner_type' => null,
        'status' => null,
        'email_verified' => null,
        'email_verified_at' => null,
        'totp_secret' => null,
        'reset_token' => null,
        'reset_token_expires' => null,
        'registration_type' => null,
        'ip_address' => null,
        'updated_at' => null,
        'created_at' => null
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer',
        'first_name' => '?string',
        'last_name' => '?string',
        'role_id' => 'integer',
        'owner_id' => 'integer',
        'email_verified' => 'boolean',
    ];
}