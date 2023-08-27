<?php

namespace Kczereczon\Scoreboard\Utils;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;

interface ScoreboardInterface
{
    public function getMatches(): array;
    public function getSummary();
}