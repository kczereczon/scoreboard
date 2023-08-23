<?php

namespace Kczereczon\Scoreboard\Tests\Entity;

use Kczereczon\Scoreboard\Entity\SimpleTeamEntity;
use PHPUnit\Framework\TestCase;

class SimpleTeamEntityTest extends TestCase
{

    public function testGetName()
    {
        $team = new SimpleTeamEntity(1, 'test');

        $this->assertEquals('test', $team->getName());
    }

    public function testSetName()
    {
        $team = new SimpleTeamEntity(1, 'test');
        $team->setName('test2');

        $this->assertEquals('test2', $team->getName());
    }

    public function testGetId()
    {
        $team = new SimpleTeamEntity(1, 'test');

        $this->assertEquals(1, $team->getId());
    }
}
