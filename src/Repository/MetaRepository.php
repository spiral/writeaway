<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Repository;

use Cycle\ORM\Select\Repository;
use Spiral\WriteAway\Database\Meta;

class MetaRepository extends Repository
{
    public function findMeta(string $namespace, string $view, string $code): ?Meta
    {
        /** @var Meta|null $meta */
        $meta = $this->findOne(compact('namespace', 'view', 'code'));
        return $meta;
    }
}
