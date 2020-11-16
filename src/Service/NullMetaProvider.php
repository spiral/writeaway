<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Service;

use Spiral\Writeaway\DTO\Meta;

class NullMetaProvider implements MetaProviderInterface
{
    public function provide(): Meta
    {
        return new Meta('', '');
    }
}
