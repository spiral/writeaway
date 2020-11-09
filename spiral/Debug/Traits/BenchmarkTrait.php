<?php

declare(strict_types=1);

namespace Spiral\Debug\Traits;

use Spiral\Debug\Benchmark;

trait BenchmarkTrait
{
    private function benchmark($event, string $context = ''): Benchmark
    {
        return new Benchmark(get_class($this), $event, $context);
    }
}
