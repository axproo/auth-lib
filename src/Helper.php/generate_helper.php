<?php

use CodeIgniter\I18n\Time;

if (!function_exists('generateCode')) {
    function generateCode() {
        return random_int(100000, 999999);
    }
}

if (!function_exists('generateTime')) {
    function generateTime($time = 15) {
        return Time::now()->addMinutes($time);
    }
}