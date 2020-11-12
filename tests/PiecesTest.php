<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use Psr\Http\Message\ResponseInterface;
use Spiral\Writeaway\Repository\PieceRepository;


//todo test save with locations (invalid, empty, valid)
//todo test bulk (invalid, not adding new, half-match)
class PiecesTest extends TestCase
{
    use HttpTrait;

    /**
     * @dataProvider badPayloadProvider
     * @param array $payload
     */
    public function testBadRequestedGet(array $payload): void
    {
        $this->assertSame(
            400,
            $this->post((string)$this->router->uri('writeaway:pieces:get'), $payload)->getStatusCode()
        );
    }

    public function badPayloadProvider(): iterable
    {
        return [
            [[]],
            [['type' => 'piece']]
        ];
    }

    public function testValidGet(): void
    {
        $this->post(
            (string)$this->router->uri('writeaway:pieces:get'),
            ['type' => 'piece', 'id' => 'something']
        );

        /** @var PieceRepository $pieces */
        $pieces = $this->app->get(PieceRepository::class);
        $this->assertCount(0, $pieces->select());
    }

    /**
     * @throws \JsonException
     */
    public function testSave(): void
    {
        $data = ['hello', 'world' => '!'];
        $response = $this->post(
            (string)$this->router->uri('writeaway:pieces:save'),
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
            (string)$this->router->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'something', 'data' => $data]
        );
        $response = $this->post(
            (string)$this->router->uri('writeaway:pieces:get'),
            ['type' => 'piece', 'id' => 'something']
        );

        $this->assertCreated($response, $data);
    }

    /**
     * @param ResponseInterface $response
     * @param array             $data
     * @throws \JsonException
     */
    private function assertCreated(ResponseInterface $response, array $data): void
    {
        $output = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
        $piece = $output['data'];
        $this->assertEquals($data, $piece['data']);

        /** @var PieceRepository $pieces */
        $pieces = $this->app->get(PieceRepository::class);
        $this->assertCount(1, $pieces->select());
    }
}