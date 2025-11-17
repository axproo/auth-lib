<?php 

namespace Axproo\Otp\Helpers;

class OtpGenerator
{
    /**
     * Génère un code numérique par défaut à 6 chiffres
     * @param int $length
     * @return string
     */
    public static function generate(int $length = 6) : string {
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        // Génère en toute sécurité
        $code = random_int($min, $max);

        return (string) $code;
    }
}