<?php 
namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table    = 'tenants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','email','uuid'];
    protected $returnType = 'Axproo\Auth\Entities\TenantEntity';
    protected $useTimestamps = true;
}