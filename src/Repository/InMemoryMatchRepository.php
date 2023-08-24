<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;

class InMemoryMatchRepository implements MatchRepositoryInterface
{

    /** @var MatchEntityInterface[] */
    private array $matches = [];

    public function __construct(array $matches = [])
    {
        $this->matches = $matches;
    }

    public function getAll(): array
    {
        return $this->matches;
    }

    public function getOneById(int $id): ?MatchEntityInterface
    {
        return array_filter($this->matches, static fn(MatchEntityInterface $match) => $match->getId() === $id)[0];
    }

    public function save(MatchEntityInterface $match): void
    {
        $matchId = $this->matchExists($match);

        if($matchId> 0) {
            $this->matches[$matchId] = $match;
        } else {
            $this->matches[] = $match;
        }
    }

    private function matchExists(MatchEntityInterface $match): int
    {
        foreach ($this->matches as $key => $existingMatch) {
            if($existingMatch->getId() === $match->getId()) {
                return $key;
            }
        }

        return -1;
    }
}