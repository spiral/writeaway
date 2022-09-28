<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Bootloader;

use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Security\FiltersBootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Router\GroupRegistry;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Core\InterceptableCore;
use Spiral\Cycle\Bootloader\ValidationBootloader as CycleValidationBootloader;
use Spiral\Cycle\Interceptor\CycleInterceptor;
use Spiral\Router\Route;
use Spiral\Router\Target\Action;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Spiral\Writeaway\Command\DropCommand;
use Spiral\Writeaway\Config\WriteawayConfig;
use Spiral\Writeaway\Controller;
use Spiral\Writeaway\MetaProviderInterface;
use Spiral\Writeaway\Middleware\AccessMiddleware;
use Spiral\Writeaway\Service\NullMetaProvider;

class WriteawayBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ValidationBootloader::class,
        FiltersBootloader::class,
        ValidatorBootloader::class,
        CycleValidationBootloader::class
    ];

    protected const INTERCEPTORS = [
        CycleInterceptor::class
    ];

    protected const BINDINGS = [
        MetaProviderInterface::class => NullMetaProvider::class,
    ];

    public function init(
        ConfiguratorInterface $config,
        ConsoleBootloader $console,
        TokenizerBootloader $tokenizer
    ): void {
        $config->setDefaults(
            WriteawayConfig::CONFIG,
            [
                'permission' => 'writeaway.edit',
                'routeGroup' => 'web',
                'images'     => [
                    'storageBucket' => 'uploads',
                    'thumbnail'     => ['width' => 120, 'height' => 120]
                ]
            ]
        );

        $console->addCommand(DropCommand::class);
        $tokenizer->addDirectory(dirname(__DIR__) . '/Database');
    }

    public function boot(
        CoreInterface $core,
        GroupRegistry $groups,
        ContainerInterface $container,
        WriteawayConfig $config
    ): void {
        $this->registerRoutes($core, $groups, $container, $config->getRouteGroup());
    }

    private function registerRoutes(
        CoreInterface $core,
        GroupRegistry $groups,
        ContainerInterface $container,
        string $routeGroup
    ): void {
        $names = [
            'writeaway:images:list',
            'writeaway:images:upload',
            'writeaway:images:delete',
            'writeaway:pieces:save',
            'writeaway:pieces:get',
            'writeaway:pieces:bulk',
        ];
        $patterns = [
            'writeaway:images:list'   => '/api/writeaway/images/list',
            'writeaway:images:upload' => '/api/writeaway/images/upload',
            'writeaway:images:delete' => '/api/writeaway/images/delete',
            'writeaway:pieces:save'   => '/api/writeaway/pieces/save',
            'writeaway:pieces:get'    => '/api/writeaway/pieces/get',
            'writeaway:pieces:bulk'   => '/api/writeaway/pieces/bulk',
        ];
        $actions = [
            'writeaway:images:list'   => new Action(Controller\ImageController::class, 'list'),
            'writeaway:images:upload' => new Action(Controller\ImageController::class, 'upload'),
            'writeaway:images:delete' => new Action(Controller\ImageController::class, 'delete'),
            'writeaway:pieces:save'   => new Action(Controller\PieceController::class, 'save'),
            'writeaway:pieces:get'    => new Action(Controller\PieceController::class, 'get'),
            'writeaway:pieces:bulk'   => new Action(Controller\PieceController::class, 'bulk'),
        ];
        $verbs = [
            'writeaway:images:list'   => ['GET', 'POST'],
            'writeaway:images:upload' => ['POST'],
            'writeaway:images:delete' => ['POST', 'DELETE'],
            'writeaway:pieces:save'   => ['POST'],
            'writeaway:pieces:get'    => ['GET', 'POST'],
            'writeaway:pieces:bulk'   => ['GET', 'POST'],
        ];

        foreach ($names as $name) {
            $route = new Route($patterns[$name], $actions[$name]->withCore($this->domainCore($core, $container)));
            $groups->getGroup($routeGroup)->addRoute(
                $name,
                $route->withMiddleware(AccessMiddleware::class)->withVerbs(...$verbs[$name])
            );
        }
    }

    private function domainCore(CoreInterface $core, ContainerInterface $container): InterceptableCore
    {
        $core = new InterceptableCore($core);

        foreach (static::INTERCEPTORS as $interceptor) {
            $core->addInterceptor($container->get($interceptor));
        }

        return $core;
    }
}
