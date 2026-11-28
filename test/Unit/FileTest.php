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

use Ergebnis\DataProvider;
use Ergebnis\License;
use PHPUnit\Framework;
use Symfony\Component\Filesystem;

/**
 * @covers \Ergebnis\License\File
 *
 * @uses \Ergebnis\License\Exception\InvalidFile
 * @uses \Ergebnis\License\Holder
 * @uses \Ergebnis\License\Range
 * @uses \Ergebnis\License\Template
 * @uses \Ergebnis\License\Year
 */
final class FileTest extends Framework\TestCase
{
    use License\Test\Util\Helper;

    protected function setUp(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->mkdir(self::temporaryDirectory());
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem\Filesystem();

        $filesystem->remove(self::temporaryDirectory());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty()
     */
    public function testCreateRejectsBlankOrEmptyName(string $name): void
    {
        $faker = self::faker();

        $template = License\Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = License\Range::since(
            License\Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = License\Holder::fromString($faker->name());

        $this->expectException(License\Exception\InvalidFile::class);

        License\File::create(
            $name,
            $template,
            $range,
            $holder,
        );
    }

    public function testNameReturnsName(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            __DIR__,
            $faker->slug(),
        );
        $template = License\Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = License\Range::since(
            License\Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = License\Holder::fromString($faker->name());

        $file = License\File::create(
            $name,
            $template,
            $range,
            $holder,
        );

        self::assertSame($name, $file->name());
    }

    public function testSaveSavesFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::temporaryDirectory(),
            $faker->slug(),
        );
        $template = License\Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt');
        $range = License\Range::since(
            License\Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = License\Holder::fromString($faker->name());

        $file = License\File::create(
            $name,
            $template,
            $range,
            $holder,
        );

        $file->save();

        self::assertFileExists($file->name());

        $expected = $template->toString([
            '<holder>' => $holder->toString(),
            '<range>' => $range->toString(),
        ]);

        self::assertSame($expected, \file_get_contents($file->name()));
    }
}
