<?php 
namespace Axproo\Auth\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table    = 'otps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','otp_code','expires_at'];
    protected $returnType = 'Axproo\Auth\Entities\OtpEntity';
    protected $useTimestamps = true;
}