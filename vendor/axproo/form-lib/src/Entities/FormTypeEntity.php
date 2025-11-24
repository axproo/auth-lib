<?php 
namespace Axproo\Form\Entities;

use CodeIgniter\Entity\Entity;

class FormTypeEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'description' => null,
        'updated_at' => null,
        'created_at' => null
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer'
    ];
}