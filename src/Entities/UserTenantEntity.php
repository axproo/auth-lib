<?php 
namespace Axproo\Auth\Entities;

use CodeIgniter\Entity\Entity;

class UserTenantEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'tenant_id' => null,
        'role' => null,
        'status' => null,
        'updated_at' => null,
        'created_at' => null
    ];
    
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer',
        'user_id' => 'integer',
        'tenant_id' => 'integer'
    ];
}