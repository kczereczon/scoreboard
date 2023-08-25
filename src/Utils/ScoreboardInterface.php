<?php

namespace Kczereczon\Scoreboard\Utils;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

interface ScoreboardInterface
{
    public function getMatches(): array;
    public function getMatch(int $matchId): ?MatchEntityInterface;
    public function addMatch(MatchEntityInterface $matchEntity): void;
    public function updateScore(int $matchId, int $homeScore, int $awayScore): void;
    public function getWinner(int $matchId): TeamEntityInterface;
    public function getLooser(int $matchId): TeamEntityInterface;
    public function endMatch(int $matchId): void;
    public function startMatch(int $matchId): void;
    public function render();
    public function renderSummary();
}