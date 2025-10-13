<?php 
// Chargement manuel des fichiers (pas besoin de Composer)
require __DIR__ . '/vendor/autoload.php';

// Autoload PSR-4 manuel simple
spl_autoload_register(function ($class) {
    $prefix     = 'Axproo\\Auth\\';
    $baseDir    = __DIR__ . '/src/';
    $len        = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Test réels
use Axproo\Auth\AuthService;

// Création du service
$auth = new AuthService();

// Création d'un token
$token = $auth->login([
    'id' => '100',
    'name' => 'test axproo',
    'email' => 'test@axproo.com'
]);

echo "Token généré : \n$token\n\n";

// Validation du token
$data = $auth->verify($token);

echo "Décodé : \n";
print_r($data);