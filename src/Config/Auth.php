<?php 

namespace Axproo\Auth\Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    /**
     * Forcer la vérification d'email pout Tous les utilisateurs avant connexion
     * @var bool
     */
    public bool $forceEmailVerification = true;

    /**
     * Forcer l'authentification à 2 facteurs pour tous les utilisateurs
     * @var bool
     */
    public bool $force2FA = false;
}