<?php

namespace Kczereczon\Scoreboard\Entity;

class SimpleTeamEntity implements TeamEntityInterface
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}