<?php 

namespace Axproo\LangManager;

class LangReporter
{
    public static function shwoPendingTranslation(string $outputDir, array $locales) : void {
        foreach ($locales as $locale) {
            $localeDir = rtrim($outputDir, '/') . '/' . $locale;

            if (!is_dir($localeDir)) {
                echo "Dossier langue '$locale' non trouvé.\n";
                continue;
            }

            echo "\n=== Clés ç traduire pour '$locale' ===\n";

            foreach (glob($localeDir . '/*.php') as $file) {
                $module = basename($file, '.php');
                $data = include $file;

                $flattend = Helpers::flattenArray($data);

                $pending = [];
                foreach ($flattend as $key => $value) {
                    if (strpos($value, '__TRANSLATE__') === 0) {
                        $pending[] = $key;
                    }
                }

                if ($pending) {
                    echo "Module $module:\n";
                    foreach ($pending as $key) {
                        echo "   - $key\n";
                    }
                }
            }
        }
    }
}