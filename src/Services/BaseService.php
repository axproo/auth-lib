<?php 
namespace Axproo\Auth\Services;

use Axproo\Mailer\Services\MailerService;
use Config\Services;

class BaseService
{
    protected $request;
    protected $validation;
    protected MailerService $mailer;

    protected $title;

    public function __construct() {
        $this->request = service('request');
        $this->validation = Services::validation();
        $this->mailer = new MailerService();
    }

    // Validation générique
    protected function validate(array $rules) : bool {
        if (!$this->validation->setRules($rules)->run($this->get_data_from_post())) {
            return false;
        }
        return true;
    }

    protected function respondSuccess(string $message, array $data = []) {
        return axprooResponse(200, $message, $data);
    }

    protected function respondError(string|array $message, int $code = 403, array $data = []) {
        return axprooResponse($code, $message, $data);
    }

    // Récupération des données POST /GET
    protected function get_data_from_post() : array {
        return (array) $this->request->getVar();
    }

    protected function sendEmail(string $to, string $view, array $data = []) {
        return $this->mailer->send($to, $this->title, $view, $data);
    }

    protected function setDataFromEmail($user, array $overrides = []) : array {
        return array_merge([
            'title'      => $this->title,
            'name'       => trim("{$user->first_name} {$user->last_name}"),
            'code'       => $user->code,
            'copyright'  => lang('Message.copyright.title'),
            'submessage' => 'Ce message a été envoyé automatiquement, veuillez ne pas y répondre',
            'link'       => ''
        ], $overrides);
    }
}