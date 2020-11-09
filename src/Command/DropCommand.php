<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Command;

use Cycle\ORM\TransactionInterface;
use Spiral\Console\Command;
use Spiral\WriteAway\Repository\MetaRepository;
use Spiral\WriteAway\Repository\PieceRepository;

class DropCommand extends Command
{
    protected const NAME        = 'writeaway:drop';
    protected const DESCRIPTION = 'Drop all pieces and metas from the repository';

    public function perform(
        PieceRepository $pieceRepository,
        MetaRepository $metaRepository,
        TransactionInterface $transaction
    ): void {
        $pieces = 0;
        foreach ($pieceRepository->findAll() as $piece) {
            $pieces++;
            $transaction->delete($piece);
        }

        $metas = 0;
        foreach ($metaRepository->findAll() as $meta) {
            $metas++;
            $transaction->delete($meta);
        }

        $this->output->writeln(
            "Trying to delete <comment>$pieces</comment> pieces and <comment>$metas</comment> metas..."
        );
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
