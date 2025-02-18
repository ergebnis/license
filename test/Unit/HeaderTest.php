<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit;

use Ergebnis\License\File;
use Ergebnis\License\Header;
use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Template;
use Ergebnis\License\Test;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Header
 *
 * @uses \Ergebnis\License\File
 * @uses \Ergebnis\License\Holder
 * @uses \Ergebnis\License\Range
 * @uses \Ergebnis\License\Template
 * @uses \Ergebnis\License\Url
 * @uses \Ergebnis\License\Year
 */
final class HeaderTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateWithReferenceToLicenseFileReturnsHeader(): void
    {
        $faker = self::faker();

        $template = Template::fromFile(__DIR__ . '/../../resource/header/with-reference-to-license-file.txt');
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $file = File::create(
            \sprintf(
                '%s/%s.txt',
                __DIR__,
                $faker->slug(),
            ),
            Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt'),
            $range,
            $holder,
        );
        $url = Url::fromString($faker->url());

        $header = Header::createWithReferenceToLicenseFile(
            $template,
            $range,
            $holder,
            $file,
            $url,
        );

        $expected = $template->toString([
            '<file>' => \basename($file->name()),
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
            '<url>' => $url->toString(),
        ]);

        self::assertSame($expected, $header->toString());
    }

    public function testCreateWithoutReferenceToLicenseFileReturnsHeader(): void
    {
        $faker = self::faker();

        $template = Template::fromFile(__DIR__ . '/../../resource/header/without-reference-to-license-file.txt');
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $header = Header::createWithoutReferenceToLicenseFile(
            $template,
            $range,
            $holder,
            $url,
        );

        $expected = $template->toString([
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
            '<url>' => $url->toString(),
        ]);

        self::assertSame($expected, $header->toString());
    }
}
