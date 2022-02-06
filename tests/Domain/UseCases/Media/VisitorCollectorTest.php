<?php

namespace Tests\Domain\UseCases\Media;

use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Media\MediaHostInterface;
use App\Data\UseCases\Media\MediaCollectorVisitor;
use App\Domain\Models\Assets\AbstractAsset;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MediaHostInterfaceStub implements MediaHostInterface
{
  public function assetInformation(): ?AbstractAsset
  {
    $absAsset = new class extends AbstractAsset
    {
      function getPath(): string
      {
        return "path";
      }
    };


    return $absAsset;
  }

  public function accept(MediaCollectorInterface $visitor): void
  {
    $visitor->visit($this);
  }

  public function namedBy(): string
  {
    return "named";
  }

  function jsonSerialize(): mixed
  {
    return [];
  }
}

class VisitorCollectorTest extends TestCase
{
  use ProphecyTrait;
  private MediaCollectorInterface $sut;

  function setUp(): void
  {
    $this->sut = new MediaCollectorVisitor();
  }

  public function testShouldHaveNoElementsInVisitorArraySetForEmptyAbstractAsset()
  {
    $mhi = $this->prophesize(MediaHostInterfaceStub::class);
    $mhi->assetInformation()->willReturn(null);
    $this->sut->visit($mhi->reveal());

    $this->assertEmpty($this->sut->collect());
  }

  public function testShouldHaveOneElementWhenAssetIsPresent()
  {
    $mhi = $this->prophesize(MediaHostInterfaceStub::class);

    $mhi->namedBy()->willReturn("");

    $mhi->assetInformation()->will(function () {
      $absAsset = new class extends AbstractAsset
      {
      };
      $absAsset->setPath("file");

      return $absAsset;
    });

    $this->sut->visit($mhi->reveal());

    $this->assertEquals(1, count($this->sut->collect()));
    $this->assertEquals($this->sut->collect()[0]->path(), "file");
  }

  public function testShouldHave()
  {
    $arr = [];
    for ($i = 0; $i < 5; $i++) {
      $arr[] = new MediaHostInterfaceStub();
    }

    foreach ($arr as $el) {
      $this->sut->visit($el);
    }

    $this->assertEquals(5, count($this->sut->collect()));
  }
}
