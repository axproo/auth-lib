<?php 

namespace Axproo\Auth\Services;

class AccessService extends BaseAuthService
{
    protected static $user;
    
    public static function set($data) {
        self::$user = $data;
    }

    public static function get() {
        return self::$user;
    }

    public static function uid() {
        return self::$user->uid ?? null;
    }

    public static function role() {
        return self::$user->role ?? null;
    }

    public static function uuid() {
        return self::$user->tenant ?? null;
    }

    public static function fullname() {
        return self::$user->fullname ?? null;
    }

    public static function status() {
        return self::$user->status ?? null;
    }

    public static function email() {
        return self::$user->email ?? null;
    }
}