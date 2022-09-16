<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

class WriteawayViewsBootloader extends Bootloader
{
    public function init(ViewsBootloader $views): void
    {
        $views->addDirectory('writeaway', dirname(__DIR__) . '/../views');
    }
}
