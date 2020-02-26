<?php


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\Renderer;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;

class Render {

  /** @var DocDataRepository */
  private $docDataRepo;

  /** @var TemplateRepository  */
  private $templateRepo;

  /** @var RenderRequest  */
  public $originalRequest;

  /** @var RenderRequest  */
  public $completeRequest;

  public function __construct(TemplateRepository $templateRepo, DocDataRepository $docDataRepo) {
    $this->docDataRepo = $docDataRepo;
    $this->templateRepo = $templateRepo;
  }

  public function renderDoc(RenderRequest $request) {
    if (!$request->isValid()) {
      throw new InvalidRenderRequestException;
    }
    $this->originalRequest = $request;
    $this->completeRequest = $this->buildRequestOfIds($request);

    $template = $this->templateRepo->getTemplateById($this->completeRequest->templateId);
    $docData = $this->docDataRepo->getByDocDataId($this->completeRequest->dataId);

    $compiler = TemplateCompiler::create();
    $partials = $this->templateRepo->findByDocType($template->docType . '/partial');

    foreach ($partials as $partial) {
      $compiler->addPartial(new Partial($partial->templateKey, $partial->body));
    }
    $renderer = Renderer::create();

    $body = $renderer->render($compiler->compile($template->body), $docData->data);

    $file = tmpfile();
    fwrite($file, $body);
    rewind($file);
    return $file;
  }

  protected function buildRequestOfIds(RenderRequest $request): RenderRequest {
    return RenderRequest::fromState([
        'dataId' => $this->getDataIdFromRequest($request),
        'templateId' => $this->getTemplateIdFromRequest($request)
    ]);
  }

  private function getDataIdFromRequest(RenderRequest $request) {
    if (!empty($request->dataId)) {
      return $request->dataId;
    }
    return $this->docDataRepo->lookupId($request->docType, $request->dataKey);
  }

  private function getTemplateIdFromRequest(RenderRequest $request) {
    if (!empty($request->templateId)) {
      return $request->templateId;
    }
    return $this->templateRepo->lookupId($request->docType, $request->templateKey);
  }
}