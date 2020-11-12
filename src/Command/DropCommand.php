<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Command;

use Cycle\ORM\TransactionInterface;
use Spiral\Console\Command;
use Spiral\Writeaway\Repository\PieceRepository;

class DropCommand extends Command
{
    protected const NAME        = 'writeaway:drop';
    protected const DESCRIPTION = 'Drop all pieces from the repository';

    public function perform(PieceRepository $pieceRepository, TransactionInterface $transaction): void
    {
        $pieces = 0;
        foreach ($pieceRepository->findAll() as $piece) {
            $pieces++;
            $transaction->delete($piece);
        }

        $this->output->writeln("Trying to delete <comment>$pieces</comment> pieces...");
        try {
            $transaction->run();
        } catch (\Throwable $e) {
            $this->output->writeln(
                "<fg=red>{$e->getMessage()} at {$e->getFile()}:{$e->getLine()}</fg=red>"
            );
            return;
        }

        $this->output->writeln('Done');
    }
}
