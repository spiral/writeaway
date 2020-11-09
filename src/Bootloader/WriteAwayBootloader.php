<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Keeper\Middleware\AccessMiddleware;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Router\GroupRegistry;
use Spiral\WriteAway\Config\WriteAwayConfig;

class WriteAwayBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        AnnotatedRoutesBootloader::class,
    ];

    private const CONFIG = WriteAwayConfig::CONFIG;

    public function boot(ConfiguratorInterface $config, GroupRegistry $groups): void
    {
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
            ->setPrefix($config->getConfig(self::CONFIG)['endpointPrefix'])
            ->addMiddleware(AccessMiddleware::class);
    }
}
