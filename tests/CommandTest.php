<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

class CommandTest extends TestCase
{
    use HttpTrait;

    /**
     * @throws \Throwable
     */
    public function testDrop(): void
    {
        $this->app->getConsole()->run('cycle:sync');
        $this->assertCount(0, $this->repository()->select());

        $this->post($this->uri('writeaway:pieces:save'), ['type' => 'piece', 'id' => 'name']);
        $this->assertCount(1, $this->repository()->select());

        $this->app->getConsole()->run('writeaway:drop');
        $this->assertCount(0, $this->repository()->select());
    }
}
