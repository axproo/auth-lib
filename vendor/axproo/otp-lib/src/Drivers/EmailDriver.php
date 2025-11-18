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
        $subject = $meta['subject'] ?? 'Aucun titre défini pour le mail';
        $body = $meta['body'] ?? 'test';
        $meta['code'] = $code;

        // Envoi du mail
        $sent = $this->mailer->send($receiver, $subject, $body, $meta);
        return $sent ?: false;
    }

    public function verify(string $receiver, string $code): bool
    {
        // Pour email/SMS, la vérification s'effectue via OtpRepository, donc retourne true pour interface.
        return true;
    }
}