<?php

namespace Kczereczon\Scoreboard\Entity;

interface MatchEntityInterface
{
    public function getHomeTeam(): TeamEntityInterface;
    public function getAwayTeam(): TeamEntityInterface;
    public function getHomeTeamScore(): int;
    public function getAwayTeamScore(): int;
    public function getMatchDate(): \DateTimeInterface;
    public function getId(): int;
    public function getStatus(): string;
    public function getTotalScore(): int;
}