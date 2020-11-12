<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\Piece;

use Psr\Http\Message\ResponseInterface;
use Spiral\Tests\Writeaway\HttpTrait;
use Spiral\Tests\Writeaway\TestCase;
use Spiral\Writeaway\Database\Piece;

class SaveTest extends TestCase
{
    use HttpTrait;

    /**
     * @throws \JsonException
     */
    public function testSave(): void
    {
        $data = ['hello', 'world' => '!'];
        $response = $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something', 'data' => $data]
        );

        $this->assertCreated($response, $data);
    }

    /**
     * @throws \JsonException
     */
    public function testGetSaved(): void
    {
        $data = ['hello', 'world' => '!'];
        $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something', 'data' => $data]
        );
        $response = $this->post(
            $this->uri('writeaway:pieces:get'),
            ['type' => 'piece', 'id' => 'something']
        );

        $this->assertCreated($response, $data);
    }

    /**
     * @dataProvider invalidLocationProvider
     * @param array $location
     */
    public function testInvalidLocation(array $location): void
    {
        $response = $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something'] + $location
        );
        $this->assertSame(400, $response->getStatusCode());
    }

    public function invalidLocationProvider(): iterable
    {
        return [
            [['namespace' => [123]]],
            [['namespace' => 'ns', 'view' => new \stdClass()]],
        ];
    }

    public function testEmptyLocation(): void
    {
        $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something', 'namespace' => 'ns', 'view' => '']
        );

        /** @var Piece|null $piece */
        $piece = $this->repository()->findOne();

        $this->assertInstanceOf(Piece::class, $piece);
        $this->assertCount(0, $piece->locations);
    }

    public function testFilledLocation(): void
    {
        $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something', 'namespace' => 'ns', 'view' => 'view']
        );

        /** @var Piece|null $piece */
        $piece = $this->repository()->findOne();

        $this->assertInstanceOf(Piece::class, $piece);
        $this->assertCount(1, $piece->locations);

        /** @var Piece\Location|null $location */
        $location = $piece->locations->first();

        $this->assertInstanceOf(Piece\Location::class, $location);
        $this->assertSame('ns', $location->namespace);
        $this->assertSame('view', $location->view);
    }

    /**
     * @param ResponseInterface $response
     * @param array             $data
     * @throws \JsonException
     */
    private function assertCreated(ResponseInterface $response, array $data): void
    {
        $output = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($data, $output['data']['data']);
        $this->assertCount(1, $this->repository()->select());
    }
}
