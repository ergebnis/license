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
use Ergebnis\License\Url;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Url
 *
 * @uses \Ergebnis\License\Exception\InvalidUrl
 */
final class UrlTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidUrl::class);

        Url::fromString($value);
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): \Generator
    {
        $values = [
            'string-arbitrary' => self::faker()->sentence(),
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
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsUrl(string $value): void
    {
        $url = Url::fromString($value);

        self::assertSame($value, $url->toString());
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

    /**
     * @dataProvider provideUntrimmedValue
     */
    public function testFromStringReturnsUrlWithTrimmedValue(string $value): void
    {
        $url = Url::fromString($value);

        self::assertSame(\trim($value), $url->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideUntrimmedValue(): \Generator
    {
        foreach (self::validValues() as $key => $value) {
            yield $key => [
                \sprintf(
                    " %s \n\n",
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
            'string-https' => 'https://github.com/ergebnis/php-cs-fixer-config',
            'string-http' => 'http://github.com/ergebnis/php-cs-fixer-config',
        ];
    }
}
