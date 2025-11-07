<?php 
namespace Axproo\Auth\Services;

use Axproo\Auth\Configs\Validation\AuthConfig;
use Axproo\Auth\Libraries\OtpManager;
use Axproo\Auth\Models\UserModel;
use CodeIgniter\I18n\Time;

class OtpService extends BaseService
{
    protected $validate;
    protected UserModel $users;
    protected OtpManager $otp;
    protected TokenManager $token;

    public function __construct() {
        parent::__construct();
        $this->validate = new AuthConfig;
        $this->users = new UserModel();
        $this->otp = new OtpManager();
        $this->token = new TokenManager();
    }

    public function generate() {
        $data = $this->get_data_from_post();

        // Validation des données
        if (!$this->validate($this->validate->otp)) {
            return $this->respondError($this->validation->getErrors());
        }

        // Vérification du status user
        $user = $this->users->findByEmail($data['email']);
        $user->code = $this->otp->generate($user->id);

        $this->title = lang('Message.token.otp.verify');
        $sent = $this->sendEmail($user->email, 'emails/active_account', $this->setDataFromEmail($user));

        $token = $this->request->getCookie('jwt');
        return $this->respondSuccess(lang('Message.email.sent'), [
            'email' => $user->email,
            'redirect' => $this->token->validateToken($token)->redirect ?? '/',
            'data' => $sent
        ]);
    }

    public function verified() {
        $data = $this->get_data_from_post();

        // Validation des données
        if (!$this->validate($this->validate->code)) {
            return $this->respondError($this->validation->getErrors());
        }

        // Vérification de l'existence et du status user
        $user = $this->users->findByEmail($data['email']);
        if (!$user) {
            return $this->respondError(lang('Message.user.not_found'));
        }
        if ($user->status !== 'pending' && $user->email_verified === true) {
            return $this->respondError(lang('Auth.failed.account.active'), 401);
        }

        $this->otp->check_otp($user->id, $data['code']);

        $user->status = 'active';
        $user->email_verified = true;
        $user->email_verified_at = Time::now();

        $this->users->save($user);
        $this->otp->delete_otp($user->id);

        $this->title = lang('Message.token.otp.active_account');
        $sent = $this->sendEmail($user->email, 'emails/verify', $this->setDataFromEmail($user, ['link' => '/']));

        return $this->respondSuccess(lang('Message.email.active'), [
            'email' => $user->email,
            'redirect' => $data['redirect'] ?? '/',
            'data' => $sent
        ]);
    }

    public function resend() {
        $data = $this->get_data_from_post();
        $user = $this->users->findByEmail($data['email']);

        if (!$user) {
            return $this->respondError(lang('Message.user.not_found'));
        }

        // Vérification du délai anti-abus
        if (!$this->otp->resend_otp($user->id, 5)) {
            return $this->respondError(lang('Auth.failed.otp.wait_before_resend', ['min' => 5]), 429, [
                'retry_after' => 5
            ]);
        }

        // Vérification du status de l'utilisateur
        if ($user->status === 'active') {
            return $this->respondError(lang('Auth.failed.account.active'), 401);
        }

        $user->code = $this->otp->generate($user->id);

        $this->title = lang('Message.token.otp.verify');
        $sent = $this->sendEmail($user->email, 'emails/active_account', $this->setDataFromEmail($user));

        return $this->respondSuccess(lang('Message.email.sent'), [
            'email' => $user->email,
            'redirect' => $data['redirect'] ?? '/2FA',
            'data' => $sent
        ]);
    }
}