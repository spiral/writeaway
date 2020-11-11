<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service\Meta;

use Spiral\Helpers\Strings;
use Spiral\WriteAway\DTO\Meta;

class DummyProvider implements ProviderInterface
{
    public function provide(): ?Meta
    {
        return new Meta(Strings::random(5), Strings::random(3) . '-user name');
    }
}
