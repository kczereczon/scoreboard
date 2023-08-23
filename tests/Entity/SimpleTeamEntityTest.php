<?php

namespace Kczereczon\Scoreboard\Tests\Entity;

use Kczereczon\Scoreboard\Entity\SimpleTeamEntity;
use PHPUnit\Framework\TestCase;

class SimpleTeamEntityTest extends TestCase
{

    public function testGetName()
    {
        $team = new SimpleTeamEntity('test');

        $this->assertEquals('test', $team->getName());
    }
}
