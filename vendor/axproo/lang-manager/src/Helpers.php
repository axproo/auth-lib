<?php 

namespace Axproo\LangManager;

class Helpers
{
    public static function buildNestedArray(array $flat) : array {
        $result = [];

        foreach ($flat as $fullKey => $value) {
            $keys = explode('.', $fullKey);
            $temp = &$result;

            foreach ($keys as $key) {
                if (!isset($temp[$key])) $temp[$key] = [];

                $temp = &$temp[$key];
            }

            $temp = $value;
            unset($temp);
        }

        return $result;
    }

    public static function formatArray(array $array, int $level = 0) : string {
        $indent = str_repeat('    ', $level);
        $output = "[\n";

        foreach ($array as $key => $value) {
            $output .= $indent . "    '" . addslashes($key) . "' => ";

            if (\is_array($value)) {
                $output .= self::formatArray($value, $level + 1);
            } else {
                $output .= "'" . addslashes($value) . "'";
            }

            // Ajout de la virgule systématique (PHP accepte la virgule finale)
            $output .= ",\n";
        }

        $output .= $indent . "]";

        return $output;
    }

    public static function flattenArray(array $array, string $prefix = '') : array {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : "$prefix.$key";

            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}