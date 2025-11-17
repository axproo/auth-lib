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

    // public function findUserByEmail(string $email) {
    //     $query = $this->db->table('users')
    //         ->where('email', $email)
    //         ->get()->getFirstRow();

    //     if (!$query) {
    //         throw new \Exception(lang('Users.not_found'));
    //     }

    //     switch ($query->status) {
    //         case 'blocked':
    //             $message = lang('Users.blocked');
    //             break;
    //         case 'inactive':
    //             $message = lang('Users.inactive');
    //             break;
    //         case 'active':
    //             $message = lang('Users.active');
    //             break;
    //     }

    //     if ($query->status !== 'pending' && (bool)$query->email_verified === true && $query->email_verified_at !== null) {
    //         throw new \Exception($message, 401);
    //     }
    //     return $query;
    // }
}