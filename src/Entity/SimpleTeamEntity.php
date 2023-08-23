<?php

namespace Kczereczon\Scoreboard\Entity;

class SimpleTeamEntity implements TeamEntityInterface
{
    public function __construct(private int $id, private string $name)
    {
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}