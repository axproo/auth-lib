<?php 

namespace Axproo\Auth\Filters;

use Axproo\Auth\Services\AccessService;
use Axproo\Auth\Services\AuthService;
use Axproo\Auth\Services\UserSessionService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Récupération du token depuis header ou cookie
        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unAuthorizeResponse(lang('Token.missing'));
        }
        
        if (!\is_string($token)) {
            return $this->unAuthorizeResponse(lang('Token.mal_formated'));
        }

        // Décodage du JWT
        try {
            $authService = new AuthService();
            $decoded = $authService->validateToken($token);

            // Vérifier si l'utilisateur doit compléter le 2FA
            if ($decoded->two_factor_enabled === true) {
                return $this->unAuthorizeResponse(lang('Auth.twofactor_required'), 403);
            }

            // Vérification du role obligatoire
            if (!isset($decoded->role) || empty($decoded->role)) {
                return $this->unAuthorizeResponse(lang('Token.rule_required'));
            }

            // Vérification du status utilisateur (active, pending, blocked, inactive...)
            if (!$this->checkStatus($decoded)) {
                return $this->unAuthorizeResponse(lang('Account.not_allowed'), 403);
            }

            // Vérification de session unique (empêche connexion ailleurs)
            $session = new UserSessionService();
            if (!$session->validateSession($decoded->uid, $token)) {
                return $this->unAuthorizeResponse(lang(line: 'Session.is_connected'), 409);
            }

            // Stocker l'utilisateur dans le contexte global
            AccessService::set($decoded);
        } catch (\Throwable $th) {
            return $this->unAuthorizeResponse(lang('Token.invalid'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        throw new \Exception('Not implemented');
    }

    private function extractToken(RequestInterface $request) : ?string {
        // Authorization: Bearer xxxx
        $header = $request->getHeaderLine('Authorization');
        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        // Cookie: jwt=xxxx
        $cookieHeader = $request->getHeaderLine('Cookie');
        if ($cookieHeader && preg_match('/jwt=([^;]+)/', $cookieHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function checkStatus(object $user) : bool {
        // Exemples de statuts possibles :
        // pending  → email pas vérifié
        // inactive → compte désactivé
        // blocked  → compte suspendu
        // active   → OK

        $status = strtolower($user->status ?? '');
        return \in_array($status, ['active']);
    }

    private function unAuthorizeResponse(string $response, ?int $code = 401) : ResponseInterface {
        return service('response')->setStatusCode($code)->setBody($response);
    }
}