<?php 
namespace Axproo\Auth\Services;

use Axproo\Auth\Configs\Validation\AuthConfig;
use Axproo\Auth\Libraries\OtpManager;
use Axproo\Auth\Models\UserModel;

class OtpService extends BaseService
{
    protected $validate;
    protected UserModel $users;
    protected OtpManager $otp;

    public function __construct() {
        parent::__construct();
        $this->validate = new AuthConfig;
        $this->users = new UserModel();
        $this->otp = new OtpManager();
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

        return $this->respondSuccess(lang('Message.email.sent'), [
            'email' => $user->email,
            'redirect' => $data['redirect'] ?? '/2FA',
            'data' => $sent
        ]);
    }
}