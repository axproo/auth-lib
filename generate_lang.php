<?php

/**
 * Script pour générer automatiquement les fichiers de langue (FR & EN)
 * à partir de toutes les occurrences lang('KEY') dans notre projet
 */

$projectDir = './src'; // Dossier à scanner
$outputDir  = './src/Language';

$locales = ['fr', 'en']; // Langues à générer
$langData = []; // Stocke toutes les clés trouvées

// Parcours récursif des fichiers PHP
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectDir));

foreach ($rii as $file) {
    if (!$file->isDir() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {

        if (strpos($file->getPathname(), 'Language') !== false) {
            continue;
        }

        $content = file_get_contents($file);

        preg_match_all("/lang\(['\"](.*?)['\"]\)/", $content, $matches);

        foreach ($matches[1] as $fullKey) {
            $parts = explode('.', $fullKey);

            if (count($parts) >= 2) {
                $module  = array_shift($parts);
                $subkeys = implode('.', $parts);
                $langData[$module][$subkeys] = $subkeys;
            }
        }
    }
}

/**
 * Construit un tableau PHP hiérarchique à partir de clés "a.b.c"
 */
function buildNestedArray(array $flatArray): array {
    $result = [];

    foreach ($flatArray as $fullKey => $value) {
        $keys = explode('.', $fullKey);
        $temp = &$result;

        foreach ($keys as $key) {
            if (!isset($temp[$key])) {
                $temp[$key] = [];
            }
            $temp = &$temp[$key];
        }

        $temp = $value;
        unset($temp);
    }

    return $result;
}

/**
 * Formatte un tableau PHP en texte avec syntaxe []
 * Ajoute une virgule après chaque élément (trailing comma autorisé)
 */
function formatArray(array $array, int $level = 0): string {
    $indent = str_repeat('    ', $level);
    $output = "[\n";

    foreach ($array as $key => $value) {
        $output .= $indent . '    ' . "'" . addslashes($key) . "' => ";

        if (is_array($value)) {
            $output .= formatArray($value, $level + 1);
        } else {
            $output .= "'" . addslashes($value) . "'";
        }

        // Ajout de la virgule systématique (PHP accepte la virgule finale)
        $output .= ",";
        $output .= "\n";
    }

    $output .= $indent . "]";

    return $output;
}

/**
 * Fusionne deux tableaux imbriqués : garde les anciennes traductions existantes
 */
function mergeTranslations(array $existing, array $new): array {
    foreach ($new as $key => $value) {
        if (is_array($value)) {
            if (!isset($existing[$key]) || !is_array($existing[$key])) {
                $existing[$key] = [];
            }
            $existing[$key] = mergeTranslations($existing[$key], $value);
        } else {
            // Si la clé existe déjà, on garde l'ancienne traduction
            if (!isset($existing[$key])) {
                $existing[$key] = $value;
            }
        }
    }
    return $existing;
}


// Génération des fichiers par module
foreach ($locales as $locale) {

    $localeDir = $outputDir . '/' . $locale;
    if (!is_dir($localeDir)) mkdir($localeDir, 0777, true);

    foreach ($langData as $module => $keys) {

        $nestedArray = buildNestedArray($keys);
        $formattedArray = formatArray($nestedArray);

        $filepath = $localeDir . '/' . $module . '.php';

        // Charger existant si présent
        $existing = file_exists($filepath) ? include $filepath : [];

        // Fusionner avec le nouveau tableau généré
        $finalArray = mergeTranslations($existing, $nestedArray);

        // Formater pour PHP
        $formattedArray = formatArray($finalArray);

        // Écrire le fichier fusionné
        $content = "<?php\n\nreturn " . $formattedArray . ";\n";
        file_put_contents($filepath, $content);


        // $content = "<?php\n\nreturn " . $formattedArray . ";\n";

        // file_put_contents($localeDir . '/' . $module . '.php', $content);
    }
}

echo "Fichiers de langue générés proprement pour : " . implode(', ', $locales) . " !\n";
