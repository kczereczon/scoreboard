<?php

namespace Kczereczon\Scoreboard\Entity;

class SimpleMatchEntity implements MatchEntityInterface
{

    public function __construct(
        private int $id,
        private TeamEntityInterface $homeTeam,
        private TeamEntityInterface $awayTeam,
        private int $homeTeamScore,
        private int $awayTeamScore,
        private \DateTimeInterface $matchDate,
        private string $status
    ) {
    }

    public function getHomeTeam(): TeamEntityInterface
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): TeamEntityInterface
    {
        return $this->awayTeam;
    }

    public function getHomeTeamScore(): int
    {
        return $this->homeTeamScore;
    }

    public function getAwayTeamScore(): int
    {
        return $this->awayTeamScore;
    }

    public function getMatchDate(): \DateTimeInterface
    {
        return $this->matchDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotalScore(): int
    {
        return $this->homeTeamScore + $this->awayTeamScore;
    }

    public function setHomeTeam(TeamEntityInterface $team): void
    {
        $this->homeTeam = $team;
    }

    public function setAwayTeam(TeamEntityInterface $team): void
    {
        $this->awayTeam = $team;
    }

    public function setHomeTeamScore(int $score): void
    {
        $this->homeTeamScore = $score;
    }

    public function setAwayTeamScore(int $score): void
    {
        $this->awayTeamScore = $score;
    }

    public function setMatchDate(\DateTimeInterface $date): void
    {
        $this->matchDate = $date;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}