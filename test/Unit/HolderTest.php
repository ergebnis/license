<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\License\Exception;
use Ergebnis\License\Holder;
use Ergebnis\License\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Exception\InvalidHolder
 * @covers \Ergebnis\License\Holder
 */
final class HolderTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsBlankOrEmptyValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    /**
     * @dataProvider provideMultilineValue
     */
    public function testFromStringRejectsMultilineValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    /**
     * @return \Generator<int, array{0: string}>
     */
    public static function provideMultilineValue(): iterable
    {
        $newLineCharacters = [
            "\n",
            "\r",
            "\r\n",
        ];

        foreach ($newLineCharacters as $start) {
            foreach ($newLineCharacters as $middle) {
                foreach ($newLineCharacters as $end) {
                    /** @var list<string> $words */
                    $words = self::faker()->words();

                    yield [
                        \sprintf(
                            '%s%s%s',
                            $start,
                            \implode(
                                $middle,
                                $words,
                            ),
                            $end,
                        ),
                    ];
                }
            }
        }
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsHolder(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame($value, $holder->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideUntrimmedValue
     */
    public function testFromStringReturnsHolderWithTrimmedValue(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame(\trim($value), $holder->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideUntrimmedValue(): iterable
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                \sprintf(
                    ' %s ',
                    $value,
                ),
            ];
        }
    }

    /**
     * @return array<string, string>
     */
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
