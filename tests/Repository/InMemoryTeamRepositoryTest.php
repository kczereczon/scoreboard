<?php

namespace Kczereczon\Scoreboard\Tests\Repository;

use Kczereczon\Scoreboard\Entity\SimpleTeamEntity;
use Kczereczon\Scoreboard\Entity\TeamEntityInterface;
use Kczereczon\Scoreboard\Repository\InMemoryTeamRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class InMemoryTeamRepositoryTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testGetOneByIdExists(): void
    {
        $repository = new InMemoryTeamRepository();
        $team = $this->createMock(TeamEntityInterface::class);
        $team->method('getId')->willReturn(1);

        $repository->save($team);

        $this->assertEquals($team, $repository->getOneById(1));
    }

    public function testGetOneByIdNotExists(): void
    {
        $repository = new InMemoryTeamRepository();
        $this->assertNull($repository->getOneById(1));
    }

    /**
     * @throws Exception
     */
    public function testSaveNew(): void
    {
        $repository = new InMemoryTeamRepository();
        $team = $this->createMock(TeamEntityInterface::class);
        $team->method('getId')->willReturn(1);

        $repository->save($team);
        $this->assertEquals($team, $repository->getOneById(1));
    }

    public function testSaveUpdate(): void
    {
        $repository = new InMemoryTeamRepository();

        $team = new SimpleTeamEntity(1, 'test');
        $repository->save($team);

        $this->assertEquals('test', $repository->getOneById(1)->getName());

        $team->setName('test2');
        $repository->save($team);

        $this->assertEquals('test2', $repository->getOneById(1)->getName());
    }

    public function testGetAllEmpty(): void
    {
        $repository = new InMemoryTeamRepository();
        $this->assertEmpty($repository->getAll());
    }

    /**
     * @throws Exception
     */
    public function testGetAllNotEmpty(): void
    {
        $repository = new InMemoryTeamRepository(
            [
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
            ]
        );
        $this->assertCount(2, $repository->getAll());
    }
}
