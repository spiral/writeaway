<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Config;

use Spiral\Core\InjectableConfig;

class WriteAwayConfig extends InjectableConfig
{
    public const CONFIG = 'writeaway';

    protected $config = [
        'permission'     => '',
        'images'         => [
            'storage'   => '',
            'thumbnail' => ['width' => 0, 'height' => 0]
        ]
    ];

    public function editPermission(): string
    {
        return $this->config['permission'];
    }

    public function imageStorage(): string
    {
        return $this->config['images']['storage'];
    }

    public function thumbnailWidth(): int
    {
        return $this->config['images']['thumbnail']['width'];
    }

    public function thumbnailHeight(): int
    {
        return $this->config['images']['thumbnail']['height'];
    }
}
