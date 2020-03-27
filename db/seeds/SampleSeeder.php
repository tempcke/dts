<?php


use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler;
use Phinx\Db\Table;
use Phinx\Seed\AbstractSeed;

class SampleSeeder extends AbstractSeed {
  const TYPE = 'example_type';
  const IMAGE_TYPE = self::TYPE . '/image';
  const PARTIAL_TYPE = self::TYPE . '/partial';

  /** @var string */
  private $date;
  /** @var Table */
  private $templateTable;
  /** @var Table */
  private $compiledTemplateTable;
  /** @var Table */
  private $docDataTable;
  /** @var string */
  private $mainTemplateId;

  private function setup()
  {
    $this->date = (new \DateTime())->format('Y-m-d H:i:s');
    $this->mainTemplateId = \Ramsey\Uuid\Uuid::uuid4();

    $this->templateTable = $this->table('template');
    $this->compiledTemplateTable = $this->table('compiled_template');
    $this->docDataTable = $this->table('docdata');
    $this->addDocData();
  }

  public function run() {
    $this->setup();

    $template = $this->getTemplate();
    $partials = $this->getPartials();
    $imgPartials = $this->getImagePartials();
    $compiledTemplate = $this->compileTemplate($template, array_merge($partials, $imgPartials));

    $this->saveTemplate($template);
    $this->savePartials($partials);
    $this->saveImagePartials($imgPartials);
    $this->saveCompiledTemplate($compiledTemplate);
  }

  private function saveCompiledTemplate($cTemplate) {
    $this->compiledTemplateTable->insert(
        ['template_id' => $this->mainTemplateId, 'body' => $cTemplate, 'created_at' => $this->date]
    )->save();
  }

  private function saveTemplate($template) {
    $tArr = $this->getTemplateArray('Example Template', 'example_template', $template, self::TYPE);
    $tArr['template_id'] = $this->mainTemplateId;
    $this->templateTable->insert($tArr)->save();
  }

  private function savePartials(array $partials) {
    foreach ($partials as $partial) {
      $this->templateTable->insert(
          $this->getTemplateArray($partial->name, $partial->name, $partial->template, self::PARTIAL_TYPE)
      );
    }
    $this->templateTable->save();
  }

  private function saveImagePartials(array $imgPartials) {
    foreach ($imgPartials as $partial) {
      $this->templateTable->insert(
          $this->getTemplateArray($partial->name, $partial->name, $partial->template, self::IMAGE_TYPE)
      );
    }
    $this->templateTable->save();
  }

  private function getTemplateArray($name, $key, $body, $type) {
    return [
        'template_id' => \Ramsey\Uuid\Uuid::uuid4(),
        'template_key' => $key,
        'doc_type' => $type,
        'name' => $name,
        'author' => 'SYSTEM',
        'created_at' => $this->date,
        'body' => $body
    ];
  }

  private function compileTemplate(string $template, array $partials): string {
    $compiler = TemplateCompiler::create();
    $compiler->setPartials($partials);

    return $compiler->compile($template);
  }

  private function addDocData(): void {
    $data = [
        'name' => 'Your Name',
        'company' => [
            'name' => 'Your Company',
            'address' => '123 Example St.'
        ],
        'completedOn' => $this->date
    ];
    $docData = [
        'data_id' => \Ramsey\Uuid\Uuid::uuid4(),
        'doc_type' => 'example_type',
        'data_key' => 'example_data',
        'created_at' => $this->date,
        'data' => json_encode($data)
    ];
    $this->docDataTable->insert($docData)->save();
  }

  /**
   * @return Partial[]
   */
  private function getPartials(): array {
    return [
        new Partial('sample_style', file_get_contents(__DIR__ . '/sample_templates/sample_style.template')),
    ];
  }

  /**
   * @return Partial[]
   */
  private function getImagePartials(): array {
    return [
        new Partial('sample_logo.jpg', file_get_contents(__DIR__ . '/sample_templates/sample_logo.jpg.template')),
        new Partial('sample_signature.png', file_get_contents(__DIR__ . '/sample_templates/sample_signature.png.template')),
    ];
  }

  private function getTemplate() {
    return file_get_contents(__DIR__ . '/sample_templates/sample_template.template');
  }
}
