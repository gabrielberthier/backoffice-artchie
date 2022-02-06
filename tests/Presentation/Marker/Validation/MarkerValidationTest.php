<?php

declare(strict_types=1);

namespace Tests\Presentation\Marker\Validation;

use App\Presentation\Actions\Markers\MarkerValidations\MarkerValidation;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MarkerValidationTest extends TestCase
{
    use ProphecyTrait;

    private MarkerValidation $sut;

    protected function setUp(): void
    {
        $this->sut = new MarkerValidation();
    }

    public function testShouldReturnFalseWhenFieldsAreEmpty()
    {
        $validation = $this->sut->validation();
        $body = [
            'marker' => [
                'marker_name' => '$jon',
                'marker_text' => '',
                'marker_title' => 42,
            ],
        ];
        $result = $validation->validate($body['marker']);
        assertFalse($result);
    }

    public function testShouldValidateArrayUsingAwesomeValidation()
    {
        $validation = $this->sut->validation();
        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
            ],
        ];
        $result = $validation->validate($body['marker']);
        assertTrue($result);
    }

    public function testShouldInvalidateEmptyAsset()
    {
        $validation = $this->sut->validation();
        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
                'asset' => [],
            ],
        ];
        $result = $validation->validate($body['marker']);
        assertFalse($result);
    }

    public function testShouldFailWithBadUrlInAssets()
    {
        $validation = $this->sut->validation();
        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
                'asset' => [
                    'file_name' => 'file.png',
                    'media_type' => 'png',
                    'path' => 'media/path',
                    'url' => 'badurl',
                ],
            ],
        ];
        $result = $validation->validate($body['marker']);
        assertFalse($result);
    }

    public function testShouldPassAsset()
    {
        $validation = $this->sut->validation();
        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
                'asset' => [
                    'file_name' => 'file.png',
                    'media_type' => 'png',
                    'path' => 'media/path',
                    'url' => 'https://respect-validation.readthedocs.io',
                    'original_name' => 'like ',
                ],
            ],
        ];
        $result = $validation->validate($body['marker']);
        assertTrue($result);
    }
}
