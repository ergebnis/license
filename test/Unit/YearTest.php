<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\License\Exception;
use Ergebnis\License\Year;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Year
 *
 * @uses \Ergebnis\License\Exception\InvalidYear
 */
final class YearTest extends Framework\TestCase
{
    use Helper;

    /**
     * @dataProvider provideInvalidValue
     *
     * @param string $value
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidYear::class);

        Year::fromString($value);
    }

    public function provideInvalidValue(): \Generator
    {
        $faker = self::faker();

        $values = [
            'string-arbitrary' => $faker->sentence,
            'string-blank' => '  ',
            'string-containing-year' => \sprintf(
                '%s %s %s',
                $faker->word,
                $faker->year,
                $faker->word
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
     *
     * @param string $value
     */
    public function testFromStringReturnsYear(string $value): void
    {
        $year = Year::fromString($value);

        self::assertSame($value, $year->toString());
    }

    public function provideValidValue(): \Generator
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
}
