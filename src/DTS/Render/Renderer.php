<?php


namespace HomeCEU\DTS\Render;

use mikehaertl\wkhtmlto\Pdf;
use Ramsey\Uuid\Uuid;

class Renderer {
  protected const PDF_OPTIONS = [
      'no-outline',
      'exclude-from-outline',
      'margin-bottom' => 0,
      'margin-right'  => 10,
      'margin-left'   => 5,
      'commandOptions' => ['enableXvfb' => true]
  ];

  public static function create(): self {
    return new self();
  }

  public function render(string $compiledTemplate, array $data = []): ?string {
    $file = $this->saveToTempFile($compiledTemplate);
    $fn = include($file);

    unlink($file);
    return trim($fn($data));
  }

  private function saveToTempFile(string $compiledTemplate): string {
    $fileName = tempnam(sys_get_temp_dir(), 'cert_');
    file_put_contents($fileName, "<?php {$compiledTemplate}");
    return $fileName;
  }

  public function pdf($compiledTemplate, $data = [], $options = []) {
    $pdf = new Pdf($options ?: self::PDF_OPTIONS);
    $pdf->addPage($this->render($compiledTemplate, $data));
    return $this->saveToFile($pdf);
  }

  protected function generateTemporaryPath(): string {
    return sprintf('/tmp/%s.pdf', Uuid::uuid4());
  }

  protected function saveToFile(Pdf $pdf): string {
    $path = $this->generateTemporaryPath();
    if ($pdf->saveAs($path))
      return $path;
    throw new RenderException("There was an issue rendering the PDF - {$pdf->getError()}");
  }
}
