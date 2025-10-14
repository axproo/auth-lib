<?php 
namespace Axproo\Auth\Entities;

use CodeIgniter\Entity\Entity;

class TenantEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'email' => null,
        'uuid' => null,
        'phone' => null,
        'domain' => null,
        'status' => null,
        'updated_at' => null,
        'created_at' => null
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer',
        'uuid' => '?string'
    ];
}