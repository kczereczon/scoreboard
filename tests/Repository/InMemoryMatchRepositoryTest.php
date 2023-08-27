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

    public function testGetDuringMatches()
    {
        $matches = [
            new SimpleMatchEntity(
                1,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::FINISHED
            ),
            new SimpleMatchEntity(
                2,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::FINISHED
            ),
            new SimpleMatchEntity(
                3,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::DURING
            ),
            new SimpleMatchEntity(
                4,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::CANCELLED
            ),
            new SimpleMatchEntity(
                5,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::CANCELLED
            ),
            new SimpleMatchEntity(
                6,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::CANCELLED
            )
        ];

        $inMemoryRepository = new InMemoryMatchRepository($matches);

        $this->assertCount(1, $inMemoryRepository->getDuringMatches());
    }

    public function testUpdateScoreMatchDoesntExists()
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository([
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match does not exist');

        $inMemoryMatchRepository->updateScore(1, 0, 1);
    }

    /** @dataProvider updateScoreNegativeValuesDataProvider */
    public function testUpdateScoreNegativeValueOfScores(int $newHomeScore, int $newAwayScore)
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository([
            new SimpleMatchEntity(
                1,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::FINISHED
            ),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('New score values cannot be negative');

        $inMemoryMatchRepository->updateScore(1, $newHomeScore, $newAwayScore);
    }

    public static function updateScoreNegativeValuesDataProvider()
    {
        return [
            [0, -1],
            [-1, 0],
            [-1, -1],
            [-2, 0],
            [1, -2]
        ];
    }

    public function testUpdateScoreWithoutFlush(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository([
            new SimpleMatchEntity(
                1,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::FINISHED
            ),
        ]);

        $match = $inMemoryMatchRepository->updateScore(1, 0, 1, false);

        $this->assertEquals(0, $match->getHomeTeamScore());
        $this->assertEquals(1, $match->getAwayTeamScore());
        $this->assertEquals(0, $inMemoryMatchRepository->getOneById(1)->getHomeTeamScore());
        $this->assertEquals(0, $inMemoryMatchRepository->getOneById(1)->getAwayTeamScore());
    }

    public function testUpdateScoreWithFlush(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository([
            new SimpleMatchEntity(
                1,
                $this->createMock(TeamEntityInterface::class),
                $this->createMock(TeamEntityInterface::class),
                0,
                0,
                new \DateTimeImmutable('2023-08-27 09:59:00'),
                MatchStatus::FINISHED
            ),
        ]);

        $match = $inMemoryMatchRepository->updateScore(1, 0, 1, true);

        $this->assertEquals(0, $match->getHomeTeamScore());
        $this->assertEquals(1, $match->getAwayTeamScore());
        $this->assertEquals(0, $inMemoryMatchRepository->getOneById(1)->getHomeTeamScore());
        $this->assertEquals(1, $inMemoryMatchRepository->getOneById(1)->getAwayTeamScore());
    }

    public function testEndMatchButMatchDoesntExist(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match does not exist');

        $inMemoryMatchRepository->endMatch(1);
    }

    /** @dataProvider matchStatusesOtherThanDuringDataProvider */
    public function testEndMatchButStatusIsNotValid(MatchStatus $status): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    $status
                ),
            ]
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match must be in during status');

        $inMemoryMatchRepository->endMatch(1);
    }

    public static function matchStatusesOtherThanDuringDataProvider()
    {
        return self::getSubsetOfMatchStatuesWithoutOneProvided(MatchStatus::DURING);
    }

    public function testStartMatchSuccess(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::DURING
                ),
            ]
        );

        $inMemoryMatchRepository->endMatch(1);

        $match = $inMemoryMatchRepository->getOneById(1);

        $this->assertEquals(MatchStatus::FINISHED, $match->getStatus());
    }

    public function testStartMatchButMatchDoesntExist()
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match does not exist');

        $inMemoryMatchRepository->startMatch(1);
    }

    /** @dataProvider matchStatusesOtherThanNotStartedDataProvider */
    public function testStartMatchesButMatchHaveWrongStatus(MatchStatus $status)
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    $status
                ),
            ]
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match must be in not started status');

        $inMemoryMatchRepository->startMatch(1);
    }

    public static function matchStatusesOtherThanNotStartedDataProvider()
    {
        return self::getSubsetOfMatchStatuesWithoutOneProvided(MatchStatus::NOT_STARTED);
    }

    public function testStartMatchSuccessful(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::NOT_STARTED
                ),
            ]
        );

        $inMemoryMatchRepository->startMatch(1);

        $match = $inMemoryMatchRepository->getOneById(1);

        $this->assertEquals(MatchStatus::DURING, $match->getStatus());
    }

    public function testGetWinnerMatchDoesntExists(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match does not exist');

        $inMemoryMatchRepository->getWinner(1);
    }

    /** @dataProvider matchStatusesWithoutFinishedDataProvider */
    public function testGetWinnerMatchHasNotBeenFinished(MatchStatus $status): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    $status
                ),
            ]
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match must be in finished status');

        $inMemoryMatchRepository->getWinner(1);
    }

    public function testGetWinnerSuccessfulHomeWinner(): void
    {
        $homeTeam = $this->createMock(TeamEntityInterface::class);

        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $homeTeam,
                    $this->createMock(TeamEntityInterface::class),
                    1,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::FINISHED
                ),
            ]
        );

        $this->assertEquals($homeTeam, $inMemoryMatchRepository->getWinner(1));
    }

    public function testGetWinnerSuccessfulAwayWinner(): void
    {
        $awayTeam = $this->createMock(TeamEntityInterface::class);

        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $awayTeam,
                    0,
                    1,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::FINISHED
                ),
            ]
        );

        $this->assertEquals($awayTeam, $inMemoryMatchRepository->getWinner(1));
    }

    public static function matchStatusesWithoutFinishedDataProvider()
    {
        return self::getSubsetOfMatchStatuesWithoutOneProvided(MatchStatus::FINISHED);
    }

    private static function getSubsetOfMatchStatuesWithoutOneProvided(MatchStatus $status): array
    {
        $array = array_filter(MatchStatus::cases(), fn(\UnitEnum $case) => $case != $status);
        array_walk(
            $array,
            fn(&$item) => $item = [$item]
        );

        return $array;
    }

    /** @dataProvider matchStatusesWithoutFinishedDataProvider */
    public function testGetLooserMatchHasNotBeenFinished(MatchStatus $status): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    $status
                ),
            ]
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match must be in finished status');

        $inMemoryMatchRepository->getLooser(1);
    }

    public function testGetLooserSuccessfulHomeWinner(): void
    {
        $homeTeam = $this->createMock(TeamEntityInterface::class);

        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $homeTeam,
                    $this->createMock(TeamEntityInterface::class),
                    0,
                    1,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::FINISHED
                ),
            ]
        );

        $this->assertEquals($homeTeam, $inMemoryMatchRepository->getLooser(1));
    }

    public function testGetLooserSuccessfulAwayWinner(): void
    {
        $awayTeam = $this->createMock(TeamEntityInterface::class);

        $inMemoryMatchRepository = new InMemoryMatchRepository(
            [
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $awayTeam,
                    1,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    MatchStatus::FINISHED
                ),
            ]
        );

        $this->assertEquals($awayTeam, $inMemoryMatchRepository->getLooser(1));
    }

    public function testGetLooserButMatchDoesntExist(): void
    {
        $inMemoryMatchRepository = new InMemoryMatchRepository();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Match does not exist');

        $inMemoryMatchRepository->getLooser(1);
    }

    /** @dataProvider subsetGeneratorOfMatchStatusesDataProvider */
    public function testGetMatchWithStatues(array $statuses): void
    {
        $matches = [];

        foreach (MatchStatus::cases() as $case) {
            $matches[] =
                new SimpleMatchEntity(
                    1,
                    $this->createMock(TeamEntityInterface::class),
                    $this->createMock(TeamEntityInterface::class),
                    1,
                    0,
                    new \DateTimeImmutable('2023-08-27 09:59:00'),
                    $case
                );
        }

        $inMemoryMatchRepository = new InMemoryMatchRepository(
            $matches
        );

        $this->assertCount(count($statuses), $inMemoryMatchRepository->getMatchesWithStatues($statuses));
    }

    public static function subsetGeneratorOfMatchStatusesDataProvider()
    {
        $subsets = [[]];

        foreach (MatchStatus::cases() as $element) {
            $newSubsets = [];

            foreach ($subsets as $subset) {
                $newSubset = $subset;
                $newSubset[] = $element;
                $newSubsets[] = $newSubset;
            }

            $subsets = array_merge($subsets, $newSubsets);
        }

        array_shift($subsets);
        array_walk($subsets, static fn(&$subset) => $subset = [$subset]);

        return $subsets;
    }
}
