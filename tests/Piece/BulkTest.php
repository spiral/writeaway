<?php

declare(strict_types=1);

namespace Piece;

use Spiral\Tests\Writeaway\HttpTrait;
use Spiral\Tests\Writeaway\TestCase;

class BulkTest extends TestCase
{
    use HttpTrait;

    /**
     * @dataProvider badPayloadProvider
     * @param array $payload
     */
    public function testInvalid(array $payload): void
    {
        $response = $this->post($this->uri('writeaway:pieces:bulk'), ['pieces' => $payload]);
        $this->assertSame(400, $response->getStatusCode());
    }

    public function badPayloadProvider(): iterable
    {
        return [
            [[['id' => 'something']]],
            [[['id' => ['ss'], 'type' => 't']]],
        ];
    }

    /**
     * @throws \JsonException
     */
    public function testSemiMatch(): void
    {
        $this->post(
            $this->uri('writeaway:pieces:save'),
            ['type' => 'piece', 'id' => 'code']
        );

        $this->assertCount(1, $this->repository()->select());

        $response = $this->post(
            $this->uri('writeaway:pieces:bulk'),
            [
                'pieces' => [
                    ['type' => 'piece', 'id' => 'code'],
                    ['type' => 'cms', 'id' => 'another-code'],
                ]
            ]
        );

        $output = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(1, $output['data']);
        $this->assertSame('piece', $output['data'][0]['type']);
        $this->assertSame('code', $output['data'][0]['id']);
    }
}
