<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\ConsoleBootloader;
use Spiral\Writeaway\Command\DropCommand;

class WriteawayCommandBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class,
    ];

    public function boot(ConsoleBootloader $console): void
    {
        $console->addCommand(DropCommand::class);
    }
}
