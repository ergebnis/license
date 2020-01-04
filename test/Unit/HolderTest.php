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
use Ergebnis\License\Holder;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Holder
 *
 * @uses \Ergebnis\License\Exception\InvalidHolder
 */
final class HolderTest extends Framework\TestCase
{
    use Helper;

    /**
     * @dataProvider provideBlankOrEmptyValue
     *
     * @param string $value
     */
    public function testFromStringRejectsBlankOrEmptyValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    public function provideBlankOrEmptyValue(): \Generator
    {
        $values = [
            'string-blank' => '  ',
            'string-empty' => '',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideMultilineValue
     *
     * @param string $value
     */
    public function testFromStringRejectsMultilineValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    public function provideMultilineValue(): \Generator
    {
        $newLineCharacters = [
            "\n",
            "\r",
            "\r\n",
        ];

        foreach ($newLineCharacters as $start) {
            foreach ($newLineCharacters as $middle) {
                foreach ($newLineCharacters as $end) {
                    /** @var string[] $words */
                    $words = self::faker()->words;

                    yield [
                        \sprintf(
                            '%s%s%s',
                            $start,
                            \implode(
                                $middle,
                                $words
                            ),
                            $end
                        ),
                    ];
                }
            }
        }
    }

    /**
     * @dataProvider provideValidValue
     *
     * @param string $value
     */
    public function testFromStringReturnsHolder(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame($value, $holder->toString());
    }

    public function provideValidValue(): \Generator
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideUntrimmedValue
     *
     * @param string $value
     */
    public function testFromStringReturnsHolderWithTrimmedValue(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame(\trim($value), $holder->toString());
    }

    public function provideUntrimmedValue(): \Generator
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                \sprintf(
                    ' %s ',
                    $value
                ),
            ];
        }
    }

    private static function validValues(): array
    {
        return [
            'string-first-name' => 'Andreas',
            'string-full-name' => 'Andreas Möller',
            'string-handle' => 'localheinz',
            'string-last-name' => 'Möller',
        ];
    }
}
