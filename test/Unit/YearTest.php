<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\License\Exception;
use Ergebnis\License\Test;
use Ergebnis\License\Year;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Year
 *
 * @uses \Ergebnis\License\Exception\InvalidYear
 */
final class YearTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidYear::class);

        Year::fromString($value);
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $faker = self::faker();

        $values = [
            'string-arbitrary' => $faker->sentence(),
            'string-blank' => '  ',
            'string-containing-year' => \sprintf(
                '%s %s %s',
                $faker->word(),
                $faker->year(),
                $faker->word(),
            ),
            'string-empty' => '',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsYear(string $value): void
    {
        $year = Year::fromString($value);

        self::assertSame($value, $year->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'string-end' => '0000',
            'string-start' => '9999',
            'string-today' => \date('Y'),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Year::fromString($faker->year());
        $two = Year::fromString($faker->year());

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->year();

        $one = Year::fromString($value);
        $two = Year::fromString($value);

        self::assertTrue($one->equals($two));
    }

    public function testGreaterThanReturnsFalseWhenValueIsEqual(): void
    {
        $value = self::faker()->year();

        $one = Year::fromString($value);
        $two = Year::fromString($value);

        self::assertFalse($one->greaterThan($two));
    }

    public function testGreaterThanReturnsFalseWhenValueIsLess(): void
    {
        $one = Year::fromString('2019');
        $two = Year::fromString('2020');

        self::assertFalse($one->greaterThan($two));
    }

    public function testGreaterThanReturnsTrueWhenValueIsGreater(): void
    {
        $one = Year::fromString('2020');
        $two = Year::fromString('2019');

        self::assertTrue($one->greaterThan($two));
    }
}
