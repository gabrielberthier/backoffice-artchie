<?php

declare(strict_types=1);

namespace Tests\Presentation\Validation;

use App\Presentation\Actions\Markers\MarkerValidations\MarkerValidation;
use App\Presentation\Helpers\Validation\Validators\Facade\ValidationFacade;
use App\Presentation\Helpers\Validation\Validators\Interfaces\ValidationInterface;

use function PHPUnit\Framework\assertNotNull;
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

    private ValidationInterface $sut;

    protected function setUp(): void
    {
        $facade = new ValidationFacade((new MarkerValidation())->validation());
        $this->sut = $facade->createValidations();
    }

    public function testShouldReturnFalseWhenFieldsAreEmpty()
    {
        $body = [
            'marker' => [
                'marker_name' => '$jon',
                'marker_text' => '',
                'marker_title' => 42,
            ],
        ];
        $result = $this->sut->validate($body['marker']);
        assertNotNull($result);
    }


    public function testShouldInvalidateEmptyAsset()
    {
        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
            ],
        ];
        $result = $this->sut->validate($body['marker']);
        assertNotNull($result);
        self::assertEquals($result->getMessage(), '[asset]: asset is empty');
    }


    public function testShouldFailWithBadUrlInAssets()
    {
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
        $result = $this->sut->validate($body['marker']);

        assertNotNull($result);
        $this->assertStringContainsString('[asset]: - These rules must pass for `{ "file_name": "file.png", "media_type": "png", "path": "media/path", "url": "badurl" }`', $result->getMessage());
    }

    public function testShouldPassAsset()
    {
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

        $this->assertNull($this->sut->validate($body['marker']));
    }
}
