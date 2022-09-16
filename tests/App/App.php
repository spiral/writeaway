<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App;

use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Bootloader\Security\GuardBootloader;
use Spiral\Console\Console;
use Spiral\Cycle\Bootloader as CycleOrm;
use Spiral\Distribution\Bootloader\DistributionBootloader;
use Spiral\Framework\Kernel;
use Spiral\Http\Http;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Storage\Bootloader\StorageBootloader;
use Spiral\Tests\Writeaway\App\Bootloader\AppBootloader;
use Spiral\Tests\Writeaway\App\Bootloader\GuestBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Spiral\Writeaway\Bootloader;

class App extends Kernel
{
    protected const LOAD = [
        NyholmBootloader::class,
        GuardBootloader::class,
        GuestBootloader::class,
        RouterBootloader::class,
        StorageBootloader::class,
        DistributionBootloader::class,

        CycleOrm\CycleOrmBootloader::class,
        CycleOrm\AnnotatedBootloader::class,
        CycleOrm\CommandBootloader::class,
        CycleOrm\ValidationBootloader::class,

        Bootloader\WriteawayBootloader::class,
        Bootloader\WriteawayCommandBootloader::class,
        Bootloader\WriteawayViewsBootloader::class,
        CommandBootloader::class,
        FiltersBootloader::class,
        ValidatorBootloader::class,
        AppBootloader::class
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
