<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Config;

use Spiral\Core\InjectableConfig;

class WriteawayConfig extends InjectableConfig
{
    public const CONFIG = 'writeaway';

    protected array $config = [
        'permission' => '',
        'routeGroup' => 'web',
        'images'     => [
            'storageBucket' => '',
            'thumbnail'     => ['width' => 0, 'height' => 0]
        ]
    ];

    public function editPermission(): string
    {
        return $this->config['permission'];
    }

    public function imageStorage(): string
    {
        return $this->config['images']['storageBucket'];
    }

    public function thumbnailWidth(): int
    {
        return $this->config['images']['thumbnail']['width'];
    }

    public function thumbnailHeight(): int
    {
        return $this->config['images']['thumbnail']['height'];
    }

    public function getRouteGroup(): string
    {
        return $this->config['routeGroup'];
    }
}
