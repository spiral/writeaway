<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use Psr\Http\Message\ResponseInterface;
use Spiral\Writeaway\Repository\PieceRepository;

//todo test save with locations (invalid, empty, valid)
//todo test bulk (invalid, not adding new, half-match)
class PiecesTest extends TestCase
{
    use HttpTrait;
}
