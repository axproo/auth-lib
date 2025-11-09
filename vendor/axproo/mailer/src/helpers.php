<?php 
use Axproo\Mailer\Services\MailerService;

if (! function_exists('mailer')) {
    /**
     * Retourne une instance unique du MailerService
     * 
     * @return MailerService
     */
    function mailer() : MailerService {
        static $instance = null;

        if ($instance === null) {
            $instance = new MailerService();
        }
        return $instance;
    }
}