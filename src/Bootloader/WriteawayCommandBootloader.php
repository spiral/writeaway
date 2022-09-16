<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Writeaway\Command\DropCommand;

class WriteawayCommandBootloader extends Bootloader
{
    public function init(ConsoleBootloader $console): void
    {
        $console->addCommand(DropCommand::class);
    }
}
