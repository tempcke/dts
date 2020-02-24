<?php


namespace HomeCEU\DTS\Render;

class Renderer
{
    public static function create(): self
    {
        return new self();
    }

    public function render(string $compiledTemplate, array $data = []): ?string
    {
        $file = $this->saveToTempFile($compiledTemplate);

        $fn = include($file);
        unlink($file);

        return trim($fn($data));
    }

    private function saveToTempFile(string $compiledTemplate): string
    {
        $fileName = tempnam(sys_get_temp_dir(), 'cert_');
        file_put_contents($fileName, "<?php {$compiledTemplate}");

        return $fileName;
    }
}
