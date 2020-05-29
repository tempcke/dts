<?php declare(strict_types=1);


namespace HomeCEU\DTS\Repository;


use HomeCEU\DTS\Entity\HotRender;
use HomeCEU\DTS\Persistence;

class HotRenderRepository {
  /** @var Persistence\HotRenderPersistence */
  private $persistence;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
  }

  public function getById(string $id): HotRender {
    $hr = $this->persistence->retrieve($id);
    return HotRender::fromState($hr);
  }

  public function save(HotRender $hotRender) {
    $this->persistence->persist($hotRender->toArray());
  }
}
