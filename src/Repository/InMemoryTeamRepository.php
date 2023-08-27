<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\TeamEntityInterface;
use Kczereczon\Scoreboard\Traits\EntityExists;

class InMemoryTeamRepository implements TeamRepositoryInterface
{

    use EntityExists;

    public function __construct(private array $teams = [])
    {
    }

    public function getAll(): array
    {
        return $this->teams;
    }

    public function getOneById(int $id): ?TeamEntityInterface
    {
        return array_filter($this->teams, static fn(TeamEntityInterface $team) => $team->getId() === $id)[0];
    }

    public function save(TeamEntityInterface $team): void
    {
        $teamId = $this->exists($team);

        if ($teamId > -1) {
            $this->teams[$teamId] = $team;
        } else {
            $this->teams[] = $team;
        }
    }
}