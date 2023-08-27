<?php

namespace Kczereczon\Scoreboard\Tests\Utils;

use Kczereczon\Scoreboard\Entity\MatchEntityInterface;
use Kczereczon\Scoreboard\Enums\MatchStatus;
use Kczereczon\Scoreboard\Repository\MatchRepositoryInterface;
use Kczereczon\Scoreboard\Utils\Scoreboard;
use PHPUnit\Framework\TestCase;

class ScoreboardTest extends TestCase
{

    public function testGetMatches()
    {
        $matchRepository = $this->createMock(MatchRepositoryInterface::class);
        $matchRepository->expects(self::once())->method('getMatchesWithStatues')->with(
            [
                MatchStatus::DURING,
                MatchStatus::NOT_STARTED,
                MatchStatus::FINISHED
            ]
        )->willReturn(
            [
                $this->createMock(MatchEntityInterface::class),
                $this->createMock(MatchEntityInterface::class)
            ]
        );

        $scoreboard = new Scoreboard(
            $matchRepository
        );

        $this->assertCount(2, $scoreboard->getMatches());
    }

    public function testGetSummary()
    {
        $match1 = $this->createMock(MatchEntityInterface::class);
        $match2 = $this->createMock(MatchEntityInterface::class);
        $match3 = $this->createMock(MatchEntityInterface::class);
        $match4 = $this->createMock(MatchEntityInterface::class);

        $match1->expects(self::atLeastOnce())->method('getTotalScore')->willReturn(12);
        $match1->expects(self::atLeastOnce())->method('getAddedAt')->willReturn(new \DateTime('2023-08-27 10:06:00'));
        $match2->expects(self::atLeastOnce())->method('getTotalScore')->willReturn(12);
        $match2->expects(self::atLeastOnce())->method('getAddedAt')->willReturn(new \DateTime('2023-08-27 12:06:00'));
        $match3->expects(self::atLeastOnce())->method('getTotalScore')->willReturn(10);
        $match3->expects(self::atLeastOnce())->method('getAddedAt')->willReturn(new \DateTime('2023-08-27 13:06:00'));
        $match4->expects(self::atLeastOnce())->method('getTotalScore')->willReturn(6);
        $match4->expects(self::atLeastOnce())->method('getAddedAt')->willReturn(new \DateTime('2023-08-27 14:06:00'));

        $matchRepository = $this->createMock(MatchRepositoryInterface::class);
        $matchRepository->expects(self::once())->method('getMatchesWithStatues')->with(
            [
                MatchStatus::DURING,
                MatchStatus::NOT_STARTED,
                MatchStatus::FINISHED
            ]
        )->willReturn(
            [
                $match1,
                $match2,
                $match3,
                $match4
            ]
        );

        $scoreboard = new Scoreboard(
            $matchRepository
        );

        $this->assertEquals([
            $match2,
            $match1,
            $match3,
            $match4
        ], $scoreboard->getSummary());
    }
}
