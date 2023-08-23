<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

interface TeamsRepositoryInterface
{
    /**
     * @return TeamEntityInterface[]
     */
    public function getAll(): array;
    public function getOneById(int $id): TeamEntityInterface;
    public function save(TeamEntityInterface $team): void;
}