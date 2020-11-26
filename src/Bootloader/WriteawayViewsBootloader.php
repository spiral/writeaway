<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Views\ViewsBootloader;

class WriteawayViewsBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ViewsBootloader::class,
    ];

    public function boot(ViewsBootloader $views): void
    {
        $views->addDirectory('writeaway', dirname(__DIR__) . '/../views');
    }
}
