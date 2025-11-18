<?php 
namespace Axproo\Otp\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'code',
        'receiver',
        'channel',
        'purpose',
        'target',
        'is_used',
        'expires_at'];
    protected $returnType = 'Axproo\Otp\Entities\OtpEntity';
    protected $useTimestamps = true;
}