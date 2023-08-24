<?php

namespace Kczereczon\Scoreboard\Traits;

use Kczereczon\Scoreboard\Entity\EntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

trait EntityExists
{
    private function exists(EntityInterface $entity): int
    {
        method_exists($this, 'getAll') ?: throw new \InvalidArgumentException(
            'Method getAll() does not exist in ' . get_class($this)
        );

        foreach ($this->getAll() as $key => $existingEntity) {
            if ($existingEntity->getId() === $entity->getId()) {
                return $key;
            }
        }

        return -1;
    }
}