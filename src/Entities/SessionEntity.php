<?php 

namespace Axproo\Auth\Entities;

use CodeIgniter\Entity\Entity;

class SessionEntity extends Entity
{
    protected $attributes = [
        'id'            => null,
        'user_id'       => null,
        'jwt_token'     => null,
        'user_ip'       => null,
        'user_agent'    => null,
        'last_activity' => null,
        'updated_at'    => null,
        'created_at'    => null
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer',
        'user_id' => 'integer',
        'jwt_token' => '?string',
    ];
}