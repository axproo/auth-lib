<?php 

namespace Axproo\Otp\Drivers;

use Axproo\Mailer\Services\MailerService;
use Axproo\Otp\Contracts\OtpDriverInterface;

class EmailDriver implements OtpDriverInterface
{
    protected MailerService $mailer;

    public function __construct() {
        $this->mailer = new MailerService();
    }

    /**
     * Envoi le code OTP par email via la librairie mailer
     * @param string $receiver
     * @param string $code
     * @param array $meta
     * @throws \Exception
     * @return never
     */
    public function send(string $receiver, string $code, array $meta = []): bool
    {
        $expire_time = $meta['expires_in'] ?? 300;

        $subject = $meta['subject'] ?? 'Votre code de vérification';
        $body = $meta['body'] ?? "Votre code OTP est : {$code}\nIl expire dans {$expire_time} secondes.";

        // Envoi du mail
        $sent = $this->mailer->send($receiver, $subject, $body, []);
        return $sent ?: false;
    }

    public function verify(string $receiver, string $code): bool
    {
        // Pour email/SMS, la vérification s'effectue via OtpRepository, donc retourne true pour interface.
        return true;
    }
}