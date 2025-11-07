<?php 
namespace Axproo\Auth\Services;

class UserManager extends BaseService
{
    private bool $secure = false;

    public function __construct() {
        parent::__construct();
        $this->secure = filter_var(getenv('SECURE_COOKIE'), FILTER_VALIDATE_BOOLEAN);
    }

    public function getUserAccount(?object $user, array $overrides = []) : array {
        return array_merge([
            'uid'       => $user->id,
            'email'     => $user->email,
            'user_type' => $user->user_type,
            'fullname'  => trim("{$user->first_name} {$user->last_name}") ?? lang('Auth.failed.fullname'),
            'twofa_pending' => false
        ],$overrides);
    }

    public function setCookie($token, $expire = 86400) {
        $response = service('response');

        $response->setCookie([
            'name'      => 'jwt',
            'value'     => $token,
            'expire'    => $expire, // 24h par défaut
            'httponly'  => true,
            'secure'    => $this->secure, // Mettre à true en production avec HTTPS
            'path'      => '/',
            'samesite'  => 'lax' // Lax ou Strict pour plus de sécurité
        ]);
    }

    public function getCookie() {
        $response = service('request');
        return $response->getCookie();
    }

    public function getStatus($status, ?string $token = null) {
        switch ($status) {
            case 'active': return true;
            case 'pending': return ['key' => 'account-verify?token='.$token];
            case 'inactive': throw new \Exception(lang('Auth.failed.account.inactivated'));
            case 'blocked': throw new \Exception(lang('Auth.failed.account.blocked'));
            default: throw new \Exception(lang('Auth.failed.account.unknown', ['status' => $status]));
        }
    }

    public function decodeToken(?string $user = null) {
        $cookieHeader = $this->request->getHeaderLine('Cookie');
        $token = '';
        if (preg_match('/jwt=([^;]+)/', $cookieHeader, $matches)) {
            $token = $matches[1];
        }
        $auth = new AuthService();
        $decoded = $auth->validateToken($token);
        return $decoded->$user ?? $decoded;
    }
}