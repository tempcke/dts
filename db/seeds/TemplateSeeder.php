<?php


use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler;
use Phinx\Seed\AbstractSeed;

class TemplateSeeder extends AbstractSeed {
  protected const DOC_TYPE = 'enrollment';

  protected $templateDir;
  protected $partialDir;
  protected $imageDir;

  protected function init() {
    if (!defined('APP_ROOT')) {
      define('APP_ROOT', realpath(__DIR__ . '/../../'));
    }
    $this->templateDir = APP_ROOT . '/temp_templates';
    $this->partialDir = $this->templateDir . '/partials';
    $this->imageDir = $this->templateDir . '/images';
  }

  public function run() {
    $templateTable = $this->table('template');
    $compiledTemplateTable = $this->table('compiled_template');

    $templates = $this->extractTemplates($this->templateDir, self::DOC_TYPE);
    $partials = $this->extractTemplates($this->partialDir, self::DOC_TYPE . '/partial');
    $images = $this->extractTemplates($this->imageDir, self::DOC_TYPE . '/image');

    $compiledTemplates = $this->compileTemplates($templates, $partials, $images);

    $templateTable->insert($templates)
        ->insert($partials)
        ->insert($images)
        ->save();

    $compiledTemplateTable->insert($compiledTemplates)->save();
  }

  private function extractTemplates($location, $docType) {
    $templates = [];
    foreach (scandir($location) as $file) {
      if ($this->fileIsHidden($file)) {
        continue;
      }
      $path = "$location/{$file}";
      $pathInfo = pathinfo($path);

      if ($pathInfo['extension'] == 'template') {
        $body = file_get_contents($path);
        $templates[] = $this->getTemplateArray($pathInfo, $body, $docType);
      }
    }
    return $templates;
  }

  private function compileTemplates(array $templates, array $partials, array $images) {
    $compiler = TemplateCompiler::create();
    $compiler->addHelper(\HomeCEU\DTS\Render\TemplateHelpers::equal());
    $compiler->setPartials(array_map(function ($partial) {
      return new Partial($partial['template_key'], $partial['body']);
    }, array_merge($partials, $images)));

    $compiledTemplates = [];
    foreach ($templates as $template) {
      $compiledTemplates[] = [
          'template_id' => $template['template_id'],
          'body' => $compiler->compile($template['body']),
          'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
      ];
    }
    return $compiledTemplates;
  }

  private function fileIsHidden($file): bool {
    return $file[0] == '.';
  }

  private function getTemplateArray($pathInfo, string $body, $docType): array {
    return [
        'body' => $body,
        'template_key' => $pathInfo['filename'],
        'template_id' => \Ramsey\Uuid\Uuid::uuid4(),
        'name' => ucwords(str_replace(['.', '_'], ' ', $pathInfo['filename'])),
        'author' => 'Dan',
        'doc_type' => $docType,
        'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
    ];
  }
}
