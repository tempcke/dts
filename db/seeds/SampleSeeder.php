<?php


use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler;
use Phinx\Seed\AbstractSeed;

class SampleSeeder extends AbstractSeed {
  const TYPE = 'example_type';
  const IMAGE_TYPE = self::TYPE . '/image';
  const PARTIAL_TYPE = self::TYPE . '/partial';

  private $date;

  public function run() {
    $this->date = (new \DateTime())->format('Y-m-d');
    $tTable = $this->table('template');

    $this->addDocData();
    $template = $this->getTemplate();

    $tArr = $this->getTemplateArray('Example Template', 'example_template', $template, self::TYPE);
    $tTable->insert($tArr);

    $partials = $this->getPartials();
    foreach ($partials as $partial) {
      $pArr = $this->getTemplateArray($partial->name, $partial->name, $partial->template, self::PARTIAL_TYPE);
      $tTable->insert($pArr);
    }
    $tTable->save();

    $imgPartials = $this->getImagePartials();
    foreach ($imgPartials as $partial) {
      $pArr = $this->getTemplateArray($partial->name, $partial->name, $partial->template, self::IMAGE_TYPE);
      $tTable->insert($pArr);
    }
    $tTable->save();

    $compiledTemplate = $this->compileTemplate($template, array_merge($partials, $imgPartials));
    $this->table('compiled_template')
        ->insert(['template_id' => $tArr['template_id'], 'body' => $compiledTemplate, 'created_at' => $this->date])
        ->save();
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
    $table = $this->table('docdata');

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
    $table->insert($docData)
        ->save();
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
