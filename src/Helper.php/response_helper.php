<?php 

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;

if (!function_exists('axprooResponse')) {

    function axprooResponse(int $statusCode = 200, $message = '', array $data = []) : ResponseInterface {
        $response = Services::response();

        // Si le message est un tableau d'erreurs
        if (is_array($message)) {
            $message = $message;
        }

        $responseData = [
            'status'    => $statusCode,
            'message'   => $message,
            'query'     => $data,
            'locale'    => service('request')->getLocale()
        ];

        return $response->setStatusCode($statusCode)->setJSON($responseData);
    }
}