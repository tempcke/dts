<?php


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Entity\DocData;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Render\Renderer;
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

    $body = $this->renderTemplate(
        $this->templateRepo->getTemplateById($this->completeRequest->templateId),
        $this->docDataRepo->getByDocDataId($this->completeRequest->dataId)
    );

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

  private function renderTemplate(Template $template, DocData $docData) {
    $template = $this->templateRepo->getCompiledTemplateById($template->templateId);
    return Renderer::create()->render($template->body, $docData->data);
  }
}
