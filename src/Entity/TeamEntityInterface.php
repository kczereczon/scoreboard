<?php

namespace Kczereczon\Scoreboard\Entity;

interface TeamEntityInterface extends EntityInterface
{
    public function getName(): string;
    public function setName(string $name): void;
    public function getId(): int;
}