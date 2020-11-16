<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Tests\Writeaway\App\Service\DummyMetaProvider;
use Spiral\Writeaway\Service\MetaProviderInterface;

class AppBootloader extends Bootloader
{
    protected const BINDINGS = [
        MetaProviderInterface::class => DummyMetaProvider::class
    ];
}
