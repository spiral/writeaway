<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Service;

use Spiral\Writeaway\DTO\Meta;

interface MetaProviderInterface
{
    public function provide(): Meta;
}
