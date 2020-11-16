<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\Piece;

use Spiral\Tests\Writeaway\HttpTrait;
use Spiral\Tests\Writeaway\TestCase;

class GetTest extends TestCase
{
    use HttpTrait;

    /**
     * @dataProvider badPayloadProvider
     * @param array $payload
     */
    public function testBadRequestedGet(array $payload): void
    {
        $response = $this->post($this->uri('writeaway:pieces:get'), $payload);
        $this->assertSame(400, $response->getStatusCode());
    }

    public function badPayloadProvider(): iterable
    {
        return [
            [[]],
            [['type' => 'piece']],
            [['type' => [3], 'id' => 'code']],
        ];
    }

    public function testValidGet(): void
    {
        $this->post(
            $this->uri('writeaway:pieces:get'),
            ['type' => 'piece', 'id' => 'something']
        );

        $this->assertCount(0, $this->repository()->select());
    }

    /**
     * @throws \Throwable
     */
    public function testDropCommand(): void
    {
        $this->logSchema();
        $this->assertCount(0, $this->repository()->select());

        $this->post($this->uri('writeaway:pieces:save'), ['type' => 'piece', 'id' => 'name']);
        $this->assertCount(1, $this->repository()->select());

        $this->app->getConsole()->run('writeaway:drop');
        $this->assertCount(0, $this->repository()->select());
    }
}
