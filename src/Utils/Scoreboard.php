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
        private readonly array $statuesToBeShown = [
            MatchStatus::DURING,
            MatchStatus::NOT_STARTED,
            MatchStatus::FINISHED
        ]
    ) {
    }

    public function getMatches(): array
    {
        return $this->matchRepository->getMatchesWithStatues($this->statuesToBeShown);
    }

    public function getSummary(): array
    {
        $matches = $this->getMatches();

        array_rand($matches);
        usort(
            $matches,
            static fn(MatchEntityInterface $aMatch, MatchEntityInterface $bMatch) => $aMatch->getTotalScore(
                ) + $aMatch->getAddedAt()->getTimestamp() > $bMatch->getTotalScore() + $bMatch->getAddedAt()->getTimestamp()
        );

        return $matches;
    }
}