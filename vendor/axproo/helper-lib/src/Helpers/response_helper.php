<?php

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Réponse intégrée à CodeIgniter
 */
if (!function_exists('axprooResponse')) {

    function axprooResponse(int $statusCode = 200, $message = '', array $data = [], array $action = []) : ResponseInterface {
        $response = Services::response();
        $msg = $message;

        // Si le message est un tableau d'erreur
        if (is_array($message)) {
            $msg = $message;
        }

        $responseBody = [
            'status'    => $statusCode,
            'message'   => $msg,
            'query'     => $data,
            'action'    => $action,
            'locale'    => service('request')->getLocale()
        ];
        return $response->setStatusCode($statusCode)->setJSON($responseBody);
    }
}

/**
 * Réponse simple (sans dépendance)
 */
if (!function_exists('json_response')) {
    function json_response($data = [], int $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => $status,
            'data' => $data
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}