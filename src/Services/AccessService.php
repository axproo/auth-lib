<?php 
namespace Axproo\Auth\Services;

use CodeIgniter\HTTP\IncomingRequest;

class AccessService
{
    protected IncomingRequest $request;

    private static $user;

    public function __construct(IncomingRequest $request) {
        $this->request = $request;
    }

    public static function set($data) {
        self::$user = $data;
    }

    public static function get() {
        return self::$user;
    }

    public static function uid() {
        return self::$user->uid ?? null;
    }

    public static function uuid() {
        return self::$user->tenant_id ?? null;
    }

    public static function role() {
        return self::$user->role ?? null;
    }

    public static function fullname() {
        return self::$user->fullname ?? null;
    }

    public static function user_type() {
        return self::$user->user_type ?? null;
    }
}