<?php
namespace Axproo\Auth\Filters;

use Axproo\Auth\Services\AccessService;
use Axproo\Auth\Services\AuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Exécuté avant la route
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier et extraire le Bearer Token
        $header = $request->getHeaderLine('Authorization');
        $token = $this->extractToken($header);

        // Si le token est absent dans le header, on tente de le recupérer dans le cookie "jwt"
        if (!$token) {
            $cookieHeader = $request->getHeaderLine('Cookie');
            if (preg_match('/jwt=([^;]+)/', $cookieHeader, $matches)) {
                $token = $matches[1];
            }
        }

        // Si le token n'existe pas, on retourne une erreur (token missing)
        if (!$token) {
            return $this->unauthorizedResponse("Access denied! ". lang('Message.token.missing'));
        }

        // Si le token est mal formaté, on retourne une erreur (token format failed)
        if (!is_string($token)) {
            return $this->unauthorizedResponse("Access denied! " . lang('Message.token.failed.format'));
        }

        // Décodage du JWT
        try {
            $authService = new AuthService();
            $decoded = $authService->validateToken($token);

            if (isset($decoded->twofa_pending) && $decoded->twofa_pending === true) {
                return $this->unauthorizedResponse("Access denied! " . lang('Message.token.failed.verify'));
            }

            if (!isset($decoded->role)) {
                return $this->unauthorizedResponse("Access denied! " . lang('Message.token.failed.role'));
            }

            AccessService::set($decoded);
        } catch (\Throwable $th) {
            return $this->unauthorizedResponse("Access denied! " . lang('Message.token.invalid'));
        }
    }

    /**
     * Exécuté après la route (optionnel)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }

    private function extractToken(?string $header) : ?string {
        if ($header && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function unauthorizedResponse(string $message) : ResponseInterface {
        return service('response')->setStatusCode(401)->setBody($message);
    }
}