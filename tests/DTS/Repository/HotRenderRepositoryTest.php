<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Repository;


use HomeCEU\DTS\Db;
use HomeCEU\DTS\Entity\HotRender;
use HomeCEU\DTS\Persistence\HotRenderPersistence;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\HotRenderRepository;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class HotRenderRepositoryTest extends TestCase {
  private $persistence;
  private $repo;
  private $db;

  protected function setUp(): void {
    parent::setUp();
    $this->db = Db::connection();
    $this->db->beginTransaction();

    $this->persistence = new HotRenderPersistence($this->db);
    $this->repo = new HotRenderRepository($this->persistence);
  }

  protected function tearDown(): void {
    $this->db->rollBack();
    parent::tearDown();
  }

  public function testGetById(): void {
    $request = $this->fakeHotRenderRequestArray();
    $this->persistence->persist($request);

    $entity = HotRender::fromState($request);
    $hotRender = $this->repo->getById($request['requestId']);
    Assert::assertEquals($entity, $hotRender);
  }

  public function testSave(): void {
    $hotRender = HotRender::fromState($this->fakeHotRenderRequestArray());
    $this->repo->save($hotRender);

    Assert::assertEquals($hotRender->toArray(), $this->persistence->retrieve($hotRender->requestId));
  }

  protected function fakeHotRenderRequestArray(): array {
    return [
        'requestId' => $this->persistence->generateId()->toString(),
        'template' => '<?php /* a compiled template */ ?>',
        'data' => ['name' => 'test'],
        'createdAt' => new \DateTime('yesterday')
    ];
  }
}
