<?php 

namespace Axproo\Otp\Models;

use Axproo\Otp\Entities\UserEntity;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'two_factor_enabled',
        'totp_secret',
        'totp_uri',
    ];
    protected $returnType = UserEntity::class;
    protected $useTimestamps = true;
}