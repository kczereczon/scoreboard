<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;

interface MatchRepositoryInterface
{
    /**
     * @return MatchEntityInterface[]
     */
    public function getAll(): array;
    public function getOneById(int $id): MatchEntityInterface;
    public function save(MatchEntityInterface $match): void;
}