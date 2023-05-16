<?php

namespace Tests\Presentation\Validation;

use App\Presentation\Actions\Markers\MarkerValidations\AssetValidation;
use PHPUnit\Framework\TestCase;

class AssetValidationTest extends TestCase
{
  private AssetValidation $sut;

  function setUp(): void
  {
    $this->sut = new AssetValidation();
  }

  public function testShouldFailForEmptyAsset()
  {
    $asset = [];
    $this->assertFalse($this->sut->validation()->validate($asset));
  }
}
