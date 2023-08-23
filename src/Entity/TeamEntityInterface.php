<?php

namespace Kczereczon\Scoreboard\Entity;

interface TeamEntityInterface
{
    public function getName(): string;
    public function setName(string $name): void;
    public function getId(): int;
}