<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\Piece;

use Spiral\Tests\Writeaway\HttpTrait;
use Spiral\Tests\Writeaway\TestCase;
use Spiral\Writeaway\Repository\PieceRepository;

class GetTest extends TestCase
{
    use HttpTrait;

    /**
     * @dataProvider badPayloadProvider
     * @param array $payload
     */
    public function testBadRequestedGet(array $payload): void
    {
        $response = $this->post((string)$this->router->uri('writeaway:pieces:get'), $payload);
        $this->assertSame(400, $response->getStatusCode());
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
}
