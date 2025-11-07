<?php 
namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class UserModel extends model
{
    protected $table    = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['email','password','name','role_id','status','email_verified','email_verified_at'];
    protected $returnType = 'Axproo\Auth\Entities\UserEntity';
    protected $useTimestamps = true;

    public function findByEmail(string $email) : ?object {
        return $this->where('email', $email)->first();
    }
}