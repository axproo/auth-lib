<?php

namespace Axproo\Auth\Filters;

use Axproo\Auth\Services\AccessService;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;
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
            $otp = new TokenManager();
            $decoded = $otp->validateToken($token);

            // Vérification optionnelle selon rôle
            if (!empty($arguments)) {
                $requiredRoles = is_array($arguments) ? $arguments : ['arguments'];
                if (!in_array($decoded->role ?? '', $requiredRoles)) {
                    return $this->unAuthorizeResponse(lang('Users.rule_required'), 403);
                }
            }

            // Vérification de session unique (empêche connexion ailleurs)
            $session = new UserSessionService();
            if (!$session->validateSession($decoded->uid, $token)) {
                return $this->unAuthorizeResponse(lang(line: 'Session.is_connected'), 409);
            }

            // Stocker l'utilisateur dans le contexte global
            AccessService::set($decoded);
        } catch (\Throwable $e) {
            $session = new UserSessionService();

            if ($token) {
                $session->destroySession($token);
            }
            $session->clearCookie();

            return $this->unAuthorizeResponse($e->getMessage());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        throw new \Exception('Not implemented');
    }

    /* -------------------------- Tools ----------------------------- */

    private function extractToken(RequestInterface $request): ?string
    {
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

    private function unAuthorizeResponse(string $response, ?int $code = 401): ResponseInterface
    {
        return service('response')->setStatusCode($code)->setBody($response);
    }
}
