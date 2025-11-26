<?php 

namespace Axproo\LangManager;

class Scanner
{
    public static function scan(string $projectDir) : array {
        $langData = [];
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($projectDir));

        foreach ($rii as $file) {
            if ($file->isDir() || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            if (strpos($file->getPathname(), 'Language') !== false) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            preg_match_all("/lang\(['\"](.*?)['\"]\)/", $content, $matches);

            foreach ($matches[1] as $fullKey) {
                $parts = explode('.', $fullKey);

                if (\count($parts) < 2) continue;

                $module = array_shift($parts);
                $subkeys = implode('.', $parts);

                $langData[$module][$subkeys] = $subkeys;
            }
        }
        return $langData;
    }
}