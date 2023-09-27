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

use Ergebnis\DataProvider;
use Ergebnis\License\Exception;
use Ergebnis\License\Holder;
use Ergebnis\License\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Holder::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidHolder::class)]
final class HolderTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'blank')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'empty')]
    public function testFromStringRejectsBlankOrEmptyValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    #[Framework\Attributes\DataProvider('provideMultilineValue')]
    public function testFromStringRejectsMultilineValue(string $value): void
    {
        $this->expectException(Exception\InvalidHolder::class);

        Holder::fromString($value);
    }

    /**
     * @return \Generator<int, array{0: string}>
     */
    public static function provideMultilineValue(): \Generator
    {
        $newLineCharacters = [
            "\n",
            "\r",
            "\r\n",
        ];

        foreach ($newLineCharacters as $start) {
            foreach ($newLineCharacters as $middle) {
                foreach ($newLineCharacters as $end) {
                    /** @var array<string> $words */
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

    #[Framework\Attributes\DataProvider('provideValidValue')]
    public function testFromStringReturnsHolder(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame($value, $holder->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidValue(): \Generator
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideUntrimmedValue')]
    public function testFromStringReturnsHolderWithTrimmedValue(string $value): void
    {
        $holder = Holder::fromString($value);

        self::assertSame(\trim($value), $holder->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideUntrimmedValue(): \Generator
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
