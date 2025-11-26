<?php 

namespace Axproo\LangManager;

class FileGenerator
{
    public function save(string $filePath, array $data) : void {
        $dir = dirname($filePath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $formatted = Helpers::formatArray($data);
        $content = "<?php\n\nreturn " . $formatted .";\n";

        file_put_contents($filePath . '.php', $content);
    }
}