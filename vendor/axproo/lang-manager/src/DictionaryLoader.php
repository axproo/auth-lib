<?php 

namespace Axproo\LangManager;

class DictionaryLoader
{
    protected array $dict = [];
    
    public function __construct() {
        $base = __DIR__ . '/dictionaries';

        foreach (glob($base . "/*.php") as $file) {

            $name = pathinfo($file, PATHINFO_FILENAME);
            $this->dict[$name] = include $file;
        }
    }

    public function translate(string $key, string $locale = 'fr') : string {
        $map = "en-$locale";

        // Dictionnaire existant ?
        if (isset($this->dict[$map][$key])) {
            return $this->dict[$map][$key];
        }

        // Pas de traduction -> retourne la clé brute
        return $key;
    }
}