<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service\Meta;

use Spiral\WriteAway\DTO\Meta;

interface ProviderInterface
{
    public function provide(): ?Meta;
}
