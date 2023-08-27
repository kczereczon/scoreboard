<?php

namespace Kczereczon\Scoreboard\Utils;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;
use Kczereczon\Scoreboard\Enums\MatchStatus;
use Kczereczon\Scoreboard\Repository\MatchRepositoryInterface;

class Scoreboard implements ScoreboardInterface
{
    public function __construct(
        private MatchRepositoryInterface $matchRepository,
        private array $statuesToBeShown = [
            MatchStatus::DURING,
            MatchStatus::NOT_STARTED,
            MatchStatus::FINISHED
        ]
    ) {
    }

    public function addMatch(MatchEntityInterface $matchEntity): void
    {
        $exists = $this->matchRepository->getOneById($matchEntity->getId());

        if ($exists) {
            throw new \RuntimeException('Match already exists');
        }

        $this->matchRepository->save($matchEntity);
    }

    public function getMatches(): array
    {
        return $this->matchRepository->getMatchesWithStatues($this->statuesToBeShown);
    }

    public function getMatch(int $matchId): ?MatchEntityInterface
    {
        return $this->matchRepository->getOneById($matchId);
    }

    public function updateScore(int $matchId, int $homeScore, int $awayScore): void
    {
        $this->matchRepository->updateScore($matchId, $homeScore, $awayScore);
    }

    public function getWinner(int $matchId): TeamEntityInterface
    {
        return $this->matchRepository->getWinner($matchId);
    }

    public function getLooser(int $matchId): TeamEntityInterface
    {
        return $this->matchRepository->getLooser($matchId);
    }

    public function endMatch(int $matchId): void
    {
        $this->matchRepository->endMatch($matchId);
    }

    public function startMatch(int $matchId): void
    {
        $this->matchRepository->startMatch($matchId);
    }

    public function getSummary()
    {
        // TODO: Implement getSummary() method.
    }
}