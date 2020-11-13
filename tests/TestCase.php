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
    protected App $app;
    private RouterInterface $router;

    /**
     * @throws \Throwable
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->app = $this->makeApp(['DEBUG' => true]);

        /** @var RouterInterface $router */
        $router = $this->app->get(RouterInterface::class);
        $this->router = $router;

        $this->app->getConsole()->run('cycle:sync');
    }

    /**
     * @param array $env
     * @return App
     * @throws \Throwable
     */
    protected function makeApp(array $env = []): App
    {
        $config = [
            'app'    => __DIR__ . '/App/',
            'root'   => __DIR__ . '/App/',
            'config' => __DIR__ . '/config/',
            'public' => __DIR__ . '/public/',
        ];

        return App::init($config, new Environment($env), false);
    }

    protected function uri(string $name): string
    {
        return (string)$this->router->uri($name);
    }

    protected function repository(): PieceRepository
    {
        return $this->app->get(PieceRepository::class);
    }
}
