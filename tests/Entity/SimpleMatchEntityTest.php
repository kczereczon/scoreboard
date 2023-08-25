<?php

namespace Kczereczon\Scoreboard\Tests\Entity;

use Kczereczon\Scoreboard\Entity\SimpleMatchEntity;
use Kczereczon\Scoreboard\Enums\MatchStatus;
use PHPUnit\Framework\TestCase;

class SimpleMatchEntityTest extends TestCase
{

    public function testGetHomeTeamScore()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(1, $match->getHomeTeamScore());
    }

    public function testGetMatchDate()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(new \DateTimeImmutable('2023-08-23 19:53:00'), $match->getMatchDate());
    }

    public function testGetId()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(1, $match->getId());
    }

    public function testGetAwayTeamScore()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(2, $match->getAwayTeamScore());
    }

    public function testGetAwayTeam()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertInstanceOf(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class, $match->getAwayTeam());
    }

    public function testGetTotalScore()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(3, $match->getTotalScore());
    }

    public function testGetHomeTeam()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertInstanceOf(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class, $match->getHomeTeam());
    }

    public function testGetStatus()
    {
        $match = $this->assembleSimpleMatchEntity();

        $this->assertEquals(MatchStatus::FINISHED, $match->getStatus());
    }

    public function testSetHomeTeamScore()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setHomeTeamScore(3);

        $this->assertEquals(3, $match->getHomeTeamScore());
    }

    public function testSetAwayTeamScore()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setAwayTeamScore(4);

        $this->assertEquals(4, $match->getAwayTeamScore());
    }

    public function testSetMatchDate()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setMatchDate(new \DateTimeImmutable('2023-08-23 20:53:00'));

        $this->assertEquals(new \DateTimeImmutable('2023-08-23 20:53:00'), $match->getMatchDate());
    }

    public function testSetStatus()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setStatus(MatchStatus::FINISHED);

        $this->assertEquals(MatchStatus::FINISHED, $match->getStatus());
    }

    public function testSetHomeTeam()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setHomeTeam($this->createMock(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class));

        $this->assertInstanceOf(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class, $match->getHomeTeam());
    }

    public function testSetAwayTeam()
    {
        $match = $this->assembleSimpleMatchEntity();
        $match->setAwayTeam($this->createMock(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class));

        $this->assertInstanceOf(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class, $match->getAwayTeam());
    }


    private function assembleSimpleMatchEntity(): SimpleMatchEntity {
        return new SimpleMatchEntity(
            1,
            $this->createMock(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class),
            $this->createMock(\Kczereczon\Scoreboard\Entity\TeamEntityInterface::class),
            1,
            2,
            new \DateTimeImmutable('2023-08-23 19:53:00'),
            MatchStatus::FINISHED
        );
    }

}
