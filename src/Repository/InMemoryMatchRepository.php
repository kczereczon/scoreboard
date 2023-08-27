<?php

namespace Kczereczon\Scoreboard\Repository;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;
use Kczereczon\Scoreboard\Enums\MatchStatus;
use Kczereczon\Scoreboard\Traits\EntityExists;

class InMemoryMatchRepository implements MatchRepositoryInterface
{

    use EntityExists;

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
        $matchId = $this->exists($match);

        if ($matchId > -1) {
            $this->matches[$matchId] = $match;
        } else {
            $this->matches[] = $match;
        }
    }

    public function getDuringMatches(): array
    {
        return array_filter(
            $this->matches,
            static fn(MatchEntityInterface $match) => $match->getStatus() === MatchStatus::DURING
        );
    }

    public function updateScore(int $matchId, int $homeScore, int $awayScore, bool $flush = true): MatchEntityInterface
    {
        $match = $this->getOneById($matchId);

        if(!$match) {
            throw new \RuntimeException('Match does not exist');
        }

        $match = clone ($match);

        $match->setHomeTeamScore($homeScore);
        $match->setAwayTeamScore($awayScore);

        if($flush) {
            $this->save($match);
        }

        return $match;
    }

    public function endMatch(int $matchId, bool $flush = true): void
    {
        $match = $this->getOneById($matchId);

        if (!$match) {
            throw new \RuntimeException('Match does not exist');
        }

        if($match->getStatus() !== MatchStatus::DURING) {
            throw new \RuntimeException('Match must be in during status');
        }

        $match->setStatus(MatchStatus::FINISHED);

        if($flush) {
            $this->save($match);
        }
    }

    public function startMatch(int $matchId, bool $flush = true): void
    {
        $match = $this->getOneById($matchId);

        if (!$match) {
            throw new \RuntimeException('Match does not exist');
        }

        if($match->getStatus() !== MatchStatus::NOT_STARTED) {
            throw new \RuntimeException('Match must be in not started status');
        }

        $match->setStatus(MatchStatus::DURING);

        if($flush) {
            $this->save($match);
        }
    }

    public function getWinner(int $matchId): TeamEntityInterface
    {
        $match = $this->getOneById($matchId);

        if(!$match) {
            throw new \RuntimeException('Match does not exist');
        }

        if($match->getStatus() !== MatchStatus::FINISHED) {
            throw new \RuntimeException('Match must be in finished status');
        }

        if($match->getHomeTeamScore() > $match->getAwayTeamScore()) {
            return $match->getHomeTeam();
        }

        return $match->getAwayTeam();
    }

    public function getLooser(int $matchId): TeamEntityInterface
    {
        $match = $this->getOneById($matchId);

        if(!$match) {
            throw new \RuntimeException('Match does not exist');
        }

        if($match->getStatus() !== MatchStatus::FINISHED) {
            throw new \RuntimeException('Match must be in finished status');
        }

        if($match->getHomeTeamScore() < $match->getAwayTeamScore()) {
            return $match->getHomeTeam();
        }

        return $match->getAwayTeam();
    }
}