<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Spiral\Boot\Environment;
use Spiral\Router\RouterInterface;
use Spiral\Tests\Writeaway\App\App;

/**
 * @requires function \Spiral\Framework\Kernel::init
 */
abstract class TestCase extends BaseTestCase
{
    protected App $app;
    protected RouterInterface $router;

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
            'config' => __DIR__ . '/config/',
            'root'   => __DIR__ . '/App/',
            'app'    => __DIR__ . '/App/',
        ];

        return App::init($config, new Environment($env), false);
    }
}
