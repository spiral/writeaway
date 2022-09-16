<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Tests\Writeaway\App\Interceptor\ValidationInterceptor;

class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [
        CoreInterface::class => [self::class, 'domainCore']
    ];

    protected const INTERCEPTORS = [
        ValidationInterceptor::class
    ];
}
