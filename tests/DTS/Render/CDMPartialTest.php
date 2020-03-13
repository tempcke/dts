<?php declare(strict_types=1);


namespace DTS\Render;


use HomeCEU\DTS\Render\Partial;
use HomeCEU\Tests\DTS\Render\TestCase;

class CDMPartialTest extends TestCase {
  public function testCDMTemplateShowing(): void {
    $template = '{{> cdm }}';
    $partial = new Partial('cdm', "{{#if approvals.cdm}}
{{#with approvals.cdm as |cdm|}}
<table width=\"100%\">
    <tr>
        <td width=\"25%\">
            <span>{{> cdm.png }}</span>
        </td>
        <td width=\"75%\">
            <span class=\"char-style-override-5\">
            {{#with cdm.[0] as |approval|~}}
                This program has been approved for fulfilling the continuing education requirements of the Certifying Board for Dietary Managers (CBDM). Granting prior approval does not constitute an endorsement of the program content or its program sponsor.
            {{~/with}}
            </span>
        </td>
    </tr>
</table>
{{/with}}
{{/if}}
");

    $this->compiler->setPartials([$partial, $p2]);
    $cTemplate = $this->compiler->compile($template);

    $str = $this->render($cTemplate, ['approvals' => ['cdm' => 'hey']]);

    dump(['str' => $str]);
  }
}
