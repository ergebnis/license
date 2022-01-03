<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Exception;

use Ergebnis\License\Exception\InvalidRange;
use Ergebnis\License\Year;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Exception\InvalidRange
 *
 * @uses \Ergebnis\License\Year
 */
final class InvalidRangeTest extends Framework\TestCase
{
    public function testStartYearGreaterThanEndYearReturnsInvalidRange(): void
    {
        $start = Year::fromString('2020');
        $end = Year::fromString('2019');

        $exception = InvalidRange::startYearGreaterThanEndYear(
            $start,
            $end,
        );

        $expected = \sprintf(
            'Start year "%s" can not be greater than end year "%s".',
            $start->toString(),
            $end->toString(),
        );

        self::assertSame($expected, $exception->getMessage());
    }

    public function testStartYearGreaterThanCurrentYearReturnsInvalidRange(): void
    {
        $start = Year::fromString('2025');
        $current = Year::fromString('2020');

        $exception = InvalidRange::startYearGreaterThanCurrentYear(
            $start,
            $current,
        );

        $expected = \sprintf(
            'Start year "%s" can not be greater than current year "%s".',
            $start->toString(),
            $current->toString(),
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
