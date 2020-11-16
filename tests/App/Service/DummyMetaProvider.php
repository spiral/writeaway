<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App\Service;

use Spiral\Helpers\Strings;
use Spiral\Writeaway\DTO\Meta;
use Spiral\Writeaway\Service\MetaProviderInterface;

class DummyMetaProvider implements MetaProviderInterface
{
    public function provide(): Meta
    {
        return new Meta(Strings::random(5), Strings::random(3) . '-user name');
    }
}
