<?php 

require __DIR__ . '/vendor/autoload.php';

use Axproo\LangManager\LangManager;

$manager = new LangManager();

$manager->run(
    projectDir: './src',
    outputDir: './src/Language',
    locales: ['en','fr']
);

echo "Fichiers de langue générés avec succès !\n";