<?php

use Phinx\Migration\AbstractMigration;

class TemplateMigration extends AbstractMigration {
  protected $templateDir;
  protected $partialDir;

  protected function init() {
    parent::init();

    if (!defined('APP_ROOT')) {
      define('APP_ROOT', realpath(__DIR__ . '/../../'));
    }
    $this->templateDir = APP_ROOT . '/temp_templates';
    $this->partialDir = $this->templateDir . '/accreditation_partials';
  }

  public function up() {
    $table = $this->table('template');
    $table->insert($this->extractTemplates($this->templateDir, 'certificate'));
    $table->insert($this->extractTemplates($this->partialDir, 'certificate/partial'));
    $table->save();
  }

  public function down() {
    $this->execute('DELETE FROM template;');
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

  private function fileIsHidden($file): bool {
    return $file[0] == '.';
  }

  private function getTemplateArray($pathInfo, string $body, $docType): array {
    return [
        'body' => $body,
        'template_key' => str_replace('.', '-', $pathInfo['filename']),
        'template_id' => \Ramsey\Uuid\Uuid::uuid1(),
        'name' => ucwords(str_replace(['.', '_'], ' ', $pathInfo['filename'])),
        'author' => 'System',
        'doc_type' => $docType,
        'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
    ];
  }
}
