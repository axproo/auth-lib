<?php 
namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class UserTenantModel extends model
{
    protected $table    = 'users_tenants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','tenant_id','role','status'];
    protected $returnType = 'Axproo\Auth\Entities\UserTenantEntity';
    protected $useTimestamps = true;
}