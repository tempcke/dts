<?php


namespace HomeCEU\Tests\DTS\Template;

use HomeCEU\DTS\Render\Partial;

class PartialTest extends TestCase {
  public function testCreatePartial(): void
  {
    $partialTemplate = '{{#with student }}{{student.first_name}} {{student.last_name}}{{/with}}';
    $partial = new Partial('student_info', $partialTemplate);

    $this->assertEquals('student_info', $partial->name);
    $this->assertEquals($partialTemplate, $partial->template);
  }
}
