<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Spiral\Boot\Environment;
use Spiral\Router\RouterInterface;
use Spiral\Tests\Writeaway\App\App;
use Spiral\Writeaway\Repository\PieceRepository;

/**
 * @requires function \Spiral\Framework\Kernel::init
 */
abstract class TestCase extends BaseTestCase
{
    protected ?App $app = null;
    private bool $synced = false;

    /**
     * @throws \Throwable
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->makeApp();
        $this->sync();
    }

    protected function uri(string $name): string
    {
        return (string)$this->router()->uri($name);
    }

    protected function repository(): PieceRepository
    {
        return $this->app->get(PieceRepository::class);
    }

    private function router(): RouterInterface
    {
        return $this->app->get(RouterInterface::class);
    }

    /**
     * @throws \Throwable
     */
    private function makeApp(): void
    {
        if ($this->app === null) {
            $config = [
                'app'    => __DIR__ . '/App/',
                'root'   => __DIR__ . '/App/',
                'config' => __DIR__ . '/config/',
                'public' => __DIR__ . '/public/',
            ];

            /** @var App $app */
            $app = App::init($config, new Environment(['DEBUG' => true]), false);
            $this->app = $app;
        }
    }

    /**
     * @throws \Throwable
     */
    private function sync(): void
    {
        if (!$this->synced) {
            $this->app->getConsole()->run('cycle:sync');
            $this->synced = true;
        }
    }
}
