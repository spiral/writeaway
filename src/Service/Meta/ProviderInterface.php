<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Service\Meta;

use Spiral\Writeaway\DTO\Meta;

interface ProviderInterface
{
    public function provide(): ?Meta;
}
