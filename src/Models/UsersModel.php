<?php 

namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['email','password','name','role_id','status','email_verified','email_verified_at'];
    protected $returnType = 'Axproo\Auth\Entities\UsersEntity';
    protected $useTimestamps = true;
}