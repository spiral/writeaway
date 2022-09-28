<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\Bootloader;

use Spiral\Core\Container;
use Spiral\Router\GroupRegistry;
use Spiral\Router\RouteGroup;
use Spiral\Tests\Writeaway\App\App;
use Spiral\Tests\Writeaway\TestCase;
use Spiral\Writeaway\Config\WriteawayConfig;

final class WriteawayBootloaderTest extends TestCase
{
    public function testRoutesShouldBeRegisteredInDefaultGroup(): void
    {
        /** @var RouteGroup $group */
        $group = $this->app->get(GroupRegistry::class)->getGroup('web');

        $this->assertTrue($group->hasRoute('writeaway:images:list'));
        $this->assertTrue($group->hasRoute('writeaway:images:upload'));
        $this->assertTrue($group->hasRoute('writeaway:images:delete'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:save'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:get'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:bulk'));
    }

    public function testRoutesShouldBeRegisteredInCustomGroup(): void
    {
        $app = App::create([
            'app' => \dirname(__DIR__) . '/App/',
            'root' => \dirname(__DIR__) . '/App/',
            'config' => \dirname(__DIR__) . '/config/',
        ]);
        $app->booting(static function (Container $container): void {
            $container->bind(WriteawayConfig::class, new WriteawayConfig(['routeGroup' => 'foo']));
        });
        $app->run();

        /** @var RouteGroup $group */
        $group = $app->get(GroupRegistry::class)->getGroup('foo');

        $this->assertTrue($group->hasRoute('writeaway:images:list'));
        $this->assertTrue($group->hasRoute('writeaway:images:upload'));
        $this->assertTrue($group->hasRoute('writeaway:images:delete'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:save'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:get'));
        $this->assertTrue($group->hasRoute('writeaway:pieces:bulk'));

        /** @var RouteGroup $group */
        $group = $app->get(GroupRegistry::class)->getGroup('web');

        $this->assertFalse($group->hasRoute('writeaway:images:list'));
        $this->assertFalse($group->hasRoute('writeaway:images:upload'));
        $this->assertFalse($group->hasRoute('writeaway:images:delete'));
        $this->assertFalse($group->hasRoute('writeaway:pieces:save'));
        $this->assertFalse($group->hasRoute('writeaway:pieces:get'));
        $this->assertFalse($group->hasRoute('writeaway:pieces:bulk'));
    }
}
