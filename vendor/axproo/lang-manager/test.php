<?php 

/**
 * Si vous lancer les test à partir de composer, vous pouvez faire ceci:
 * require __DIR__ '/vendor/autoload.php';
 * composer dump-autoload
 * en suite lancer dans votre CLI: php exampe.php
 */
require __DIR__ . '/vendor/autoload.php';

use Axproo\LangManager\LangManager;

$manager = new LangManager();

$manager->run(
    projectDir: './tests',
    outputDir: './tests/Language',
    locales: ['en', 'fr', 'es']
);

echo "Fichiers de langue générés avec succès !\n";