<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Bootloader\Security\ValidationBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Domain\CycleInterceptor;
use Spiral\Domain\FilterInterceptor;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Router\GroupRegistry;
use Spiral\WriteAway\Config\WriteAwayConfig;
use Spiral\WriteAway\Middleware\AccessMiddleware;
use Spiral\WriteAway\Validation\Checker\PieceChecker;

class WriteAwayBootloader extends DomainBootloader
{
    protected const DEPENDENCIES = [
        AnnotatedRoutesBootloader::class,
    ];
    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        FilterInterceptor::class
    ];

    private const CONFIG = WriteAwayConfig::CONFIG;

    public function boot(
        ConfiguratorInterface $config,
        GroupRegistry $groups,
        CoreInterface $core,
        ValidationBootloader $validationBootloader
    ): void {
        $config->setDefaults(
            self::CONFIG,
            [
                'endpointPrefix' => 'api/writeAway/',
                'permission'     => 'writeAway.edit',
                'images'         => [
                    'storage'   => 'local',
                    'thumbnail' => ['width' => 120, 'height' => 120]
                ]
            ]
        );

        $groups->getGroup('writeAway')
            ->setCore($core)
            ->setPrefix($config->getConfig(self::CONFIG)['endpointPrefix'])
            ->addMiddleware(AccessMiddleware::class);

        $validationBootloader->addChecker('piece', PieceChecker::class);
    }
}
