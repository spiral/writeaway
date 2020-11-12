<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\Piece;

use Psr\Http\Message\ResponseInterface;
use Spiral\Tests\Writeaway\HttpTrait;
use Spiral\Tests\Writeaway\TestCase;
use Spiral\Writeaway\Repository\PieceRepository;

//todo test save with locations (invalid, empty, valid)
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
