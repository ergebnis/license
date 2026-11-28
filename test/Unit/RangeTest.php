<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\License;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Range
 *
 * @uses \Ergebnis\License\Exception\InvalidRange
 * @uses \Ergebnis\License\Exception\InvalidYear
 * @uses \Ergebnis\License\Year
 */
final class RangeTest extends Framework\TestCase
{
    public function testIncludingRequiresStartYearToBeEqualOrLessThanEndYear(): void
    {
        $start = License\Year::fromString('2020');
        $end = License\Year::fromString('2019');

        $this->expectException(License\Exception\InvalidRange::class);

        License\Range::including(
            $start,
            $end,
        );
    }

    public function testIncludingReturnsRangeWhenStartYearEqualsEndYear(): void
    {
        $start = License\Year::fromString('2020');
        $end = License\Year::fromString('2020');

        $range = License\Range::including(
            $start,
            $end,
        );

        self::assertInstanceOf(License\Year::class, $range);
        self::assertSame($start->toString(), $range->toString());
    }

    public function testIncludingReturnsRangeWhenStartYearIsLessThanEndYear(): void
    {
        $start = License\Year::fromString('2019');
        $end = License\Year::fromString('2020');

        $years = License\Range::including(
            $start,
            $end,
        );

        $expected = \sprintf(
            '%s-%s',
            $start->toString(),
            $end->toString(),
        );

        self::assertInstanceOf(License\Range::class, $years);
        self::assertSame($expected, $years->toString());
    }

    /**
     * @dataProvider provideTimeZone
     */
    public function testSinceRejectsStartYearWhenStartYearIsGreaterThanCurrentYear(\DateTimeZone $timeZone): void
    {
        $now = new \DateTimeImmutable(
            '+1 year',
            $timeZone,
        );

        $start = License\Year::fromString($now->format('Y'));

        $this->expectException(License\Exception\InvalidRange::class);

        License\Range::since(
            $start,
            $timeZone,
        );
    }

    /**
     * @dataProvider provideTimeZone
     */
    public function testSinceReturnsYearWhenStartYearEqualsCurrentYear(\DateTimeZone $timeZone): void
    {
        $now = new \DateTimeImmutable(
            'now',
            $timeZone,
        );

        $start = License\Year::fromString($now->format('Y'));

        $year = License\Range::since(
            $start,
            $timeZone,
        );

        self::assertInstanceOf(License\Year::class, $year);
        self::assertSame($start->toString(), $year->toString());
    }

    /**
     * @dataProvider provideTimeZone
     */
    public function testSinceReturnsRangeWhenStartYearIsLessThanCurrentYear(\DateTimeZone $timeZone): void
    {
        $twoYearsAgo = new \DateTimeImmutable(
            '-2 years',
            $timeZone,
        );

        $now = new \DateTimeImmutable(
            'now',
            $timeZone,
        );

        $start = License\Year::fromString($twoYearsAgo->format('Y'));

        $range = License\Range::since(
            $start,
            $timeZone,
        );

        self::assertInstanceOf(License\Range::class, $range);

        $expected = \sprintf(
            '%s-%s',
            $start->toString(),
            $now->format('Y'),
        );

        self::assertSame($expected, $range->toString());
    }

    /**
     * @return \Generator<string, array{0: \DateTimeZone}>
     */
    public static function provideTimeZone(): iterable
    {
        $values = [
            'America/New_York',
            'Europe/Berlin',
            'UTC',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                new \DateTimeZone($value),
            ];
        }
    }
}
