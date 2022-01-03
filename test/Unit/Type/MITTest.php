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

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Test;
use Ergebnis\License\Type\MIT;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use PHPUnit\Framework;
use Symfony\Component\Filesystem;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Type\MIT
 *
 * @uses \Ergebnis\License\File
 * @uses \Ergebnis\License\Header
 * @uses \Ergebnis\License\Holder
 * @uses \Ergebnis\License\Range
 * @uses \Ergebnis\License\Template
 * @uses \Ergebnis\License\Url
 * @uses \Ergebnis\License\Year
 */
final class MITTest extends Framework\TestCase
{
    use Test\Util\Helper;

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

        $baseName = \sprintf(
            '%s.txt',
            $faker->slug()
        );
        $name = \sprintf(
            '%s/%s',
            self::workspaceDirectory(),
            $baseName
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone())
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = MIT::markdown(
            $name,
            $range,
            $holder,
            $url
        );

        $expected = <<<TXT
Copyright (c) {$range->toString()} {$holder->toString()}

For the full copyright and license information, please view
the {$baseName} file that was distributed with this source code.

@see {$url->toString()}

TXT;

        self::assertSame($expected, $license->header());
    }

    public function testHeaderReturnsHeaderForTextLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s.txt',
            $faker->slug()
        );
        $baseName = \sprintf(
            '%s/%s',
            self::workspaceDirectory(),
            $name
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone())
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = MIT::text(
            $baseName,
            $range,
            $holder,
            $url
        );

        $expected = <<<TXT
Copyright (c) {$range->toString()} {$holder->toString()}

For the full copyright and license information, please view
the {$name} file that was distributed with this source code.

@see {$url->toString()}

TXT;

        self::assertSame($expected, $license->header());
    }

    public function testSaveSavesMarkdownToFileForMarkdownLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.md',
            self::workspaceDirectory(),
            $faker->slug()
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone())
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = MIT::markdown(
            $name,
            $range,
            $holder,
            $url
        );

        $license->save();

        self::assertFileExists($name);

        $expected = <<<TXT
# The MIT License (MIT)

Copyright (c) {$range->toString()} {$holder->toString()}

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the _Software_), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED **AS IS**, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

TXT;

        self::assertSame($expected, \file_get_contents($name));
    }

    public function testSaveSavesTextToFileForTextLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::workspaceDirectory(),
            $faker->slug()
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone())
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = MIT::text(
            $name,
            $range,
            $holder,
            $url
        );

        $license->save();

        self::assertFileExists($name);

        $expected = <<<TXT
The MIT License (MIT)

Copyright (c) {$range->toString()} {$holder->toString()}

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

TXT;

        self::assertSame($expected, \file_get_contents($name));
    }

    private static function workspaceDirectory(): string
    {
        return __DIR__ . '/../../../.build/test';
    }
}
