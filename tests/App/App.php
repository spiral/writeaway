<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App;

use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\Http\DiactorosBootloader;
use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Bootloader\Storage\StorageBootloader;
use Spiral\Console\Console;
use Spiral\Cycle\Bootloader as CycleOrm;
use Spiral\Framework\Kernel;
use Spiral\Http\Http;
use Spiral\Tests\Writeaway\App\Bootloader\GuestBootloader;
use Spiral\Writeaway\Bootloader;

class App extends Kernel
{
    protected const LOAD = [
        DiactorosBootloader::class,
        GuardBootloader::class,
        GuestBootloader::class,
        RouterBootloader::class,
        StorageBootloader::class,

        CycleOrm\CycleOrmBootloader::class,
        CycleOrm\AnnotatedBootloader::class,
        CycleOrm\CommandBootloader::class,

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

    public function get(string $class)
    {
        return $this->container->get($class);
    }
}
