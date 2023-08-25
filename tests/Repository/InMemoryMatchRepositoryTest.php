<?php

namespace Kczereczon\Scoreboard\Tests\Repository;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Entity\SimpleMatchEntity;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;
use Kczereczon\Scoreboard\Enums\MatchStatus;
use Kczereczon\Scoreboard\Repository\InMemoryMatchRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class InMemoryMatchRepositoryTest extends TestCase
{

    public function testGetAllEmpty(): void
    {
        $repository = new InMemoryMatchRepository();

        $this->assertEmpty($repository->getAll());
    }

    /**
     * @throws Exception
     */
    public function testGetAllFilled(): void
    {
        $repository = new InMemoryMatchRepository(
            [
                $this->createMock(MatchEntityInterface::class),
                $this->createMock(MatchEntityInterface::class),
            ]
        );

        $this->assertCount(2, $repository->getAll());
    }

    public function testGetOneByIdExists(): void
    {
        $repository = new InMemoryMatchRepository();
        $match = $this->createMock(MatchEntityInterface::class);
        $match->method('getId')->willReturn(1);

        $repository->save($match);

        $this->assertEquals($match, $repository->getOneById(1));
    }

    public function testGetOneByIdNotExists(): void
    {
        $repository = new InMemoryMatchRepository();

        $this->assertNull($repository->getOneById(1));
    }

    public function testSaveNew(): void
    {
        $repository = new InMemoryMatchRepository();
        $match = $this->createMock(MatchEntityInterface::class);
        $match->method('getId')->willReturn(1);

        $repository->save($match);

        $this->assertCount(1, $repository->getAll());
    }

    /**
     * @throws Exception
     */
    public function testSaveUpdate(): void
    {
        $repository = new InMemoryMatchRepository();

        $match = new SimpleMatchEntity(
            1,
            $this->createMock(TeamEntityInterface::class),
            $this->createMock(TeamEntityInterface::class),
            0,
            0,
            new \DateTimeImmutable("now"),
            MatchStatus::DURING
        );

        $repository->save($match);

        $this->assertEquals(0, $repository->getOneById(1)->getHomeTeamScore());

        $match->setHomeTeamScore(2);

        $repository->save($match);

        $this->assertEquals(2, $repository->getOneById(1)->getHomeTeamScore());
    }
}
