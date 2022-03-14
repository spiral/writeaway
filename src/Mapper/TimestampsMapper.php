<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Mapper;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Mapper\Mapper as BaseMapper;
use Spiral\Writeaway\Helper\DateHelper;

class TimestampsMapper extends BaseMapper
{
    /**
     * @param object $entity
     * @param Node   $node
     * @param State  $state
     * @return CommandInterface
     * @throws \Exception
     */
    public function queueCreate($entity, Node $node, State $state): CommandInterface
    {
        $cmd = parent::queueCreate($entity, $node, $state);

        $state->register('time_created', DateHelper::immutable());
        $cmd->register('time_created', DateHelper::immutable());

        $state->register('time_updated', DateHelper::immutable());
        $cmd->register('time_updated', DateHelper::immutable());

        return $cmd;
    }

    /**
     * @param object $entity
     * @param Node   $node
     * @param State  $state
     * @return CommandInterface
     * @throws \Exception
     */
    public function queueUpdate($entity, Node $node, State $state): CommandInterface
    {
        /** @var Update $cmd */
        $cmd = parent::queueUpdate($entity, $node, $state);

        $state->register('time_updated', DateHelper::immutable());
        $cmd->registerAppendix('time_updated', DateHelper::immutable());

        return $cmd;
    }
}
