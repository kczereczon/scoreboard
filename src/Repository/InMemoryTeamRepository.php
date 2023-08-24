<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

class InMemoryTeamRepository implements TeamRepositoryInterface
{

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
        $teamId = $this->teamExists($team);

        if ($teamId > 0) {
            $this->teams[$teamId] = $team;
        } else {
            $this->teams[] = $team;
        }
    }

    private function teamExists(TeamEntityInterface $team): int
    {
        foreach ($this->teams as $key => $existingTeam) {
            if ($existingTeam->getId() === $team->getId()) {
                return $key;
            }
        }

        return -1;
    }
}