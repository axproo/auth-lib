<?php 
namespace Axproo\Mailer\Services;

use Config\Services;
use Exception;

class MailerService
{
    protected $email;
    protected $parser;

    public function __construct() {
        $this->email = Services::email();
        $this->parser = service('parser');
    }

    public function send(string $to, string $subject, ?string $view = null, array $data = []) {
        $this->email->clear();
        $this->email->setTo($to);
        $this->email->setSubject($subject);

        $path = FCPATH . "email_header.png";
        if (!is_file($path)) {
            throw new \Exception(lang('Images.invalidPath'));
        }
        $this->email->attach($path);
        $cid = $this->email->setAttachmentCID($path);

        if ($view) {
            $data['year'] = date('Y');
            $data['image'] = "cid:$cid";

            $message = $this->parser->setData($data)->render($view);
        } else {
            $message = $data['message'] ?? '';
        }
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        // Envoi et gestion des erreurs
        if (!$this->email->send()) {
            $debug = $this->email->printDebugger(['header','subject','body']);

            $error = $this->extractSmtpError($debug) ?? 'Erreur SMTP inconnue';
            log_message('error', 'Erreur mail : ' . $error);

            throw new \Exception(lang('Email.SMTPDataFailure', [$error]));
        }
        return true;
    }

    protected function extractSmtpError(string $debug) : ?string {
        if (preg_match('/Unable to send email.*$/mi', $debug, $matches)) {
            return trim($matches[0]);
        }
        return null;
    }
}