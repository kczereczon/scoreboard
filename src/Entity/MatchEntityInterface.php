<?php

namespace Kczereczon\Scoreboard\Entity;

interface MatchEntityInterface extends EntityInterface
{
    public function getHomeTeam(): TeamEntityInterface;
    public function getAwayTeam(): TeamEntityInterface;
    public function getHomeTeamScore(): int;
    public function getAwayTeamScore(): int;
    public function getMatchDate(): \DateTimeInterface;
    public function getId(): int;
    public function getStatus(): string;
    public function getTotalScore(): int;
    public function setHomeTeam(TeamEntityInterface $team): void;
    public function setAwayTeam(TeamEntityInterface $team): void;
    public function setHomeTeamScore(int $score): void;
    public function setAwayTeamScore(int $score): void;
    public function setMatchDate(\DateTimeInterface $date): void;
    public function setStatus(string $status): void;
}