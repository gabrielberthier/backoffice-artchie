<?php

declare(strict_types=1);

namespace Tests\Presentation\Marker;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Presentation\Actions\Markers\MarkerBuilder\MarkerBuilder;
use App\Presentation\Actions\Markers\StoreMarkerAction;
use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException;
use App\Presentation\Protocols\Validation;
use function PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophet;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MarkerStoreTest extends TestCase
{
    use ProphecyTrait;

    private $prophet;
    private StoreMarkerAction $action;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->prophet = new Prophet();
        /** @var MarkerServiceStoreInterface */
        $service = $this->createMockService();
        $builder = new MarkerBuilder();
        $this->action = new StoreMarkerAction($service, $builder);
    }

    public function testShouldThrowErrorsWhenNoMarkerKeyIsNotProvided()
    {
        $this->expectException(UnprocessableEntityException::class);
        $request = $this->createRequest('POST', '/api/marker/');
        $action = $this->action;
        $response = $action($request, new Response(), []);
        $payload = (string) $response->getBody();

        print_r($payload);

        assertEquals($response->getStatusCode(), 422);
    }

    /**
     * @group ignore
     */
    public function testShouldRunWhenMarkerValuesAreProvided()
    {
        $this->markTestSkipped('PHPUnit will skip this test method');
        $request = $this->createRequest('POST', '/api/marker/');

        $body = [
            'marker' => [
                'marker_name' => 'something',
                'marker_text' => 'something',
                'marker_title' => 'something',
            ],
        ];

        $json_body = json_encode($body);

        echo $json_body;
        print_r(json_decode($json_body, true));

        $request->getBody()
            ->write($json_body)
        ;
        $action = $this->action;
        $response = $action($request, new Response(), []);
        $payload = (string) $response->getBody();

        print_r($payload);

        assertEquals($response->getStatusCode(), 422);
    }

    private function createValidatorService()
    {
        return $this->getMockBuilder(Validation::class)
            ->onlyMethods(['validate'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function createMockRequest(string $access, string $pass): ServerRequestInterface
    {
        return $this->createRequest('POST', '/api/marker');
        // $request->getBody()
        //     ->write(json_encode())
        // ;
        // $request->getBody()
        //     ->rewind()
        // ;
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createMockService()
    {
        return $this->getMockBuilder(MarkerServiceStoreInterface::class)
            ->onlyMethods(['insert'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
