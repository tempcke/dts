<?php declare(strict_types=1);


namespace HomeCEU\DTS\Render;


use mikehaertl\wkhtmlto\Pdf;

class RenderPDF extends Render {
  protected const PDF_OPTIONS = [
      'no-outline',
      'exclude-from-outline',
      'margin-bottom' => 0,
      'margin-right'  => 10,
      'margin-left'   => 5,
      'commandOptions' => ['enableXvfb' => true]
  ];

  private $pdf;

  private function __construct(array $options = []) {
    $this->pdf = new Pdf($options ?: self::PDF_OPTIONS);
  }

  public static function create(array $options = []): RenderInterface {
    return new static($options);
  }

  public function render(string $compiledTemplate, array $data = []): string {
    $t = $this->renderToString($compiledTemplate, $data);
    $this->pdf->addPage($t);
    return $this->saveToFile();
  }

  private function saveToFile(): string {
    $path = sprintf('/tmp/%s.pdf', uniqid());

    if ($this->pdf->saveAs($path))
      return $path;
    throw new RenderException("There was an issue rendering the PDF - {$this->pdf->getError()}");
  }
}
