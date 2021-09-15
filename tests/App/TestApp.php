<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App;

use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\Cycle;
use Spiral\Bootloader\Http\DiactorosBootloader;
use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Console\Console;
use Spiral\Framework\Kernel;
use Spiral\Http\Http;
use Spiral\Tests\Writeaway\App\Bootloader\GuestBootloader;
use Spiral\Writeaway\Bootloader;

class TestApp extends Kernel
{
    protected const LOAD = [
        DiactorosBootloader::class,
        GuardBootloader::class,
        GuestBootloader::class,
        RouterBootloader::class,

        Cycle\CycleBootloader::class,
        Cycle\AnnotatedBootloader::class,

        Bootloader\WriteawayBootloader::class,
        Bootloader\WriteawayCommandBootloader::class,
        Bootloader\WriteawayViewsBootloader::class,
        CommandBootloader::class,
        FiltersBootloader::class,
    ];

    protected const APP = [];

    public function getHttp(): Http
    {
        return $this->container->get(Http::class);
    }

    public function getConsole(): Console
    {
        return $this->container->get(Console::class);
    }

    /**
     * Get object from the container.
     *
     * @param string      $alias
     * @param string|null $context
     * @return mixed|object|null
     * @throws \Throwable
     */
    public function get($alias, string $context = null)
    {
        return $this->container->get($alias, $context);
    }
}
