<?php 

namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['email','password','name','role_id','status','email_verified','email_verified_at','two_factor_enabled','last_login_at','ip_address'];
    protected $returnType = 'Axproo\Auth\Entities\UsersEntity';
    protected $useTimestamps = true;

    public function findByEmail(string $email) {
        return $this->where('email', $email)->first();
    }
}