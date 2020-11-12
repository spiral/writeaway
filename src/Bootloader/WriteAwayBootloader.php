<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Bootloader\TokenizerBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Domain\CycleInterceptor;
use Spiral\Domain\FilterInterceptor;
use Spiral\Router\GroupRegistry;
use Spiral\WriteAway\Config\WriteAwayConfig;
use Spiral\WriteAway\Controller;
use Spiral\WriteAway\Middleware\AccessMiddleware;
use Spiral\WriteAway\Service\Meta;

class WriteAwayBootloader extends DomainBootloader
{
    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        FilterInterceptor::class
    ];
    protected const BINDINGS     = [
        Meta\ProviderInterface::class => Meta\DummyProvider::class
    ];

    private const CONFIG = WriteAwayConfig::CONFIG;

    private ConfiguratorInterface $config;
    private GroupRegistry $groups;
    private CoreInterface $core;
    private TokenizerBootloader $tokenizerBootloader;

    public function __construct(
        ConfiguratorInterface $config,
        GroupRegistry $groups,
        CoreInterface $core,
        TokenizerBootloader $tokenizerBootloader
    ) {
        $this->config = $config;
        $this->groups = $groups;
        $this->core = $core;
        $this->tokenizerBootloader = $tokenizerBootloader;
    }

    public function boot(): void
    {
        $this->initConfig();
        $this->registerRoutes();
        $this->registerDatabaseEntities();
    }

    private function initConfig(): void
    {
        $this->config->setDefaults(
            self::CONFIG,
            [
                'permission' => 'writeaway.edit',
                'images'     => [
                    'storage'   => 'local',
                    'thumbnail' => ['width' => 120, 'height' => 120]
                ]
            ]
        );
    }

    private function registerRoutes(): void
    {
        $group = $this->groups->getGroup('writeaway');

        $group->registerRoute(
            'writeaway:images:list',
            'images/list',
            Controller\ImageController::class,
            'list',
            ['GET', 'POST'],
            [],
            []
        );
        $group->registerRoute(
            'writeaway:images:upload',
            'images/upload',
            Controller\ImageController::class,
            'upload',
            ['POST'],
            [],
            []
        );
        $group->registerRoute(
            'writeaway:images:delete',
            'images/delete',
            Controller\ImageController::class,
            'delete',
            ['POST', 'DELETE'],
            [],
            []
        );
        $group->registerRoute(
            'writeaway:pieces:save',
            'pieces/save',
            Controller\PieceController::class,
            'save',
            ['POST'],
            [],
            []
        );
        $group->registerRoute(
            'writeaway:pieces:get',
            'pieces/get',
            Controller\PieceController::class,
            'get',
            ['GET', 'POST'],
            [],
            []
        );
        $group->registerRoute(
            'writeaway:pieces:bulk',
            'pieces/bulk',
            Controller\PieceController::class,
            'bulk',
            ['GET', 'POST'],
            [],
            []
        );

        $group->setCore($this->core)
            ->setPrefix('api/writeaway/')
            ->addMiddleware(AccessMiddleware::class);
    }

    private function registerDatabaseEntities(): void
    {
        $this->tokenizerBootloader->addDirectory(dirname(__DIR__) . '/Database');
    }
}
