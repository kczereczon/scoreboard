<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

interface MatchRepositoryInterface
{
    /**
     * @return MatchEntityInterface[]
     */
    public function getAll(): array;

    public function getOneById(int $id): ?MatchEntityInterface;

    public function save(MatchEntityInterface $match): void;

    public function getDuringMatches(): array;

    public function updateScore(int $matchId, int $homeScore, int $awayScore): MatchEntityInterface;

    public function getWinner(int $matchId): TeamEntityInterface;

    public function getLooser(int $matchId): TeamEntityInterface;

    public function endMatch(int $matchId): void;

    public function startMatch(int $matchId): void;
}