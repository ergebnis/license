<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Template;
use Ergebnis\License\Type\MIT;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;
use Symfony\Component\Filesystem;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Type\MIT
 *
 * @uses \Ergebnis\License\Holder
 * @uses \Ergebnis\License\Url
 * @uses \Ergebnis\License\File
 * @uses \Ergebnis\License\Header
 * @uses \Ergebnis\License\Range
 * @uses \Ergebnis\License\Template
 * @uses \Ergebnis\License\Year
 */
final class MITTest extends Framework\TestCase
{
    use Helper;

    protected function setUp(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->mkdir(self::workspaceDirectory());
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->remove(self::workspaceDirectory());
    }

    public function testHeaderReturnsHeaderForMarkdownLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::workspaceDirectory(),
            $faker->slug
        );
        $range = Range::since(
            Year::fromString($faker->year),
            new \DateTimeZone($faker->timezone)
        );
        $holder = Holder::fromString($faker->name);
        $url = Url::fromString($faker->url);

        $license = MIT::markdown(
            $name,
            $range,
            $holder,
            $url
        );

        $expected = Template::fromFile(__DIR__ . '/../../../resource/header.txt')->toString([
            '<file>' => \basename($name),
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
            '<url>' => $url->toString(),
        ]);

        self::assertSame($expected, $license->header());
    }

    public function testHeaderReturnsHeaderForTextLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::workspaceDirectory(),
            $faker->slug
        );
        $range = Range::since(
            Year::fromString($faker->year),
            new \DateTimeZone($faker->timezone)
        );
        $holder = Holder::fromString($faker->name);
        $url = Url::fromString($faker->url);

        $license = MIT::text(
            $name,
            $range,
            $holder,
            $url
        );

        $expected = Template::fromFile(__DIR__ . '/../../../resource/header.txt')->toString([
            '<file>' => \basename($name),
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
            '<url>' => $url->toString(),
        ]);

        self::assertSame($expected, $license->header());
    }

    public function testSaveSavesMarkdownToFileForMarkdownLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.md',
            self::workspaceDirectory(),
            $faker->slug
        );
        $range = Range::since(
            Year::fromString($faker->year),
            new \DateTimeZone($faker->timezone)
        );
        $holder = Holder::fromString($faker->name);
        $url = Url::fromString($faker->url);

        $license = MIT::markdown(
            $name,
            $range,
            $holder,
            $url
        );

        $license->save();

        self::assertFileExists($name);

        $expected = Template::fromFile(__DIR__ . '/../../../resource/license/MIT.md')->toString([
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
        ]);

        self::assertSame($expected, \file_get_contents($name));
    }

    public function testSaveSavesTextToFileForTextLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::workspaceDirectory(),
            $faker->slug
        );
        $range = Range::since(
            Year::fromString($faker->year),
            new \DateTimeZone($faker->timezone)
        );
        $holder = Holder::fromString($faker->name);
        $url = Url::fromString($faker->url);

        $license = MIT::text(
            $name,
            $range,
            $holder,
            $url
        );

        $license->save();

        self::assertFileExists($name);

        $expected = Template::fromFile(__DIR__ . '/../../../resource/license/MIT.txt')->toString([
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
        ]);

        self::assertSame($expected, \file_get_contents($name));
    }

    private static function workspaceDirectory(): string
    {
        return __DIR__ . '/../../../.build/test';
    }
}
