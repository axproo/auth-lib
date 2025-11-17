<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckStatus
{
    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 500);
        }

        $status = $user->status ?? 'active';

        switch ($status) {
            case 'pending':
                throw new AuthException(lang('Account.pending'), 403, [
                    'redirectTo' => '/verify-email'
                ]);

            case 'inactive': 
                throw new AuthException(lang('Account.inactive'), 403);
            case 'blocked': 
                throw new AuthException(lang('Account.blocked'), 403);
            case 'active':
            default:
                $data['user_status_checked'] = true;
                return $data;
        }
    }
}