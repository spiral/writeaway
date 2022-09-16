<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Command;

use Cycle\ORM\EntityManagerInterface;
use Spiral\Console\Command;
use Spiral\Writeaway\Repository\PieceRepository;

class DropCommand extends Command
{
    protected const NAME        = 'writeaway:drop';
    protected const DESCRIPTION = 'Drop all pieces from the repository';

    public function perform(PieceRepository $pieceRepository, EntityManagerInterface $em): int
    {
        $pieces = 0;
        foreach ($pieceRepository->findAll() as $piece) {
            $pieces++;
            $em->delete($piece);
        }

        $this->output->writeln("Trying to delete <comment>$pieces</comment> pieces...");
        try {
            $em->run();
        } catch (\Throwable $e) {
            $this->output->writeln(
                "<fg=red>{$e->getMessage()} at {$e->getFile()}:{$e->getLine()}</fg=red>"
            );
            return self::FAILURE;
        }

        $this->output->writeln('Done');

        return self::SUCCESS;
    }
}
