<?php 

namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class SessionsModel extends Model
{
    protected $table = 'users_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'user_id',
        'jwt_token',
        'user_ip',
        'user_agent',
        'last_activity'
    ];
    protected $returnType = 'Axproo\Auth\Entities\SessionEntity';
    protected $useTimestamps = true;
}