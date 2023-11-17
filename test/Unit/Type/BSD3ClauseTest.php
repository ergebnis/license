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

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License\File;
use Ergebnis\License\Header;
use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Template;
use Ergebnis\License\Test;
use Ergebnis\License\Type;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use PHPUnit\Framework;
use Symfony\Component\Filesystem;

#[Framework\Attributes\CoversClass(Type\BSD3Clause::class)]
#[Framework\Attributes\UsesClass(File::class)]
#[Framework\Attributes\UsesClass(Header::class)]
#[Framework\Attributes\UsesClass(Holder::class)]
#[Framework\Attributes\UsesClass(Range::class)]
#[Framework\Attributes\UsesClass(Template::class)]
#[Framework\Attributes\UsesClass(Url::class)]
#[Framework\Attributes\UsesClass(Year::class)]
final class BSD3ClauseTest extends Framework\TestCase
{
    use Test\Util\Helper;

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

    public function testHeaderReturnsHeaderForMarkdownLicense(): void
    {
        $faker = self::faker();

        $baseName = \sprintf(
            '%s.txt',
            $faker->slug(),
        );
        $name = \sprintf(
            '%s/%s',
            self::temporaryDirectory(),
            $baseName,
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = Type\BSD3Clause::markdown(
            $name,
            $range,
            $holder,
            $url,
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
            $faker->slug(),
        );
        $baseName = \sprintf(
            '%s/%s',
            self::temporaryDirectory(),
            $name,
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = Type\BSD3Clause::text(
            $baseName,
            $range,
            $holder,
            $url,
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
            self::temporaryDirectory(),
            $faker->slug(),
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = Type\BSD3Clause::markdown(
            $name,
            $range,
            $holder,
            $url,
        );

        $license->save();

        self::assertFileExists($name);

        $expected = <<<TXT
# BSD 3-Clause "New" or "Revised" License

Copyright (c) {$range->toString()} {$holder->toString()}.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials
   provided with the distribution.

   3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written
   permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS **AS IS** AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

TXT;

        self::assertSame($expected, \file_get_contents($name));
    }

    public function testSaveSavesTextToFileForTextLicense(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s/%s.txt',
            self::temporaryDirectory(),
            $faker->slug(),
        );
        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = Type\BSD3Clause::text(
            $name,
            $range,
            $holder,
            $url,
        );

        $license->save();

        self::assertFileExists($name);

        $expected = <<<TXT
BSD 3-Clause "New" or "Revised" License

Copyright (c) {$range->toString()} {$holder->toString()}.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

    2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials
    provided with the distribution.

    3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written
    permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

TXT;

        self::assertSame($expected, \file_get_contents($name));
    }
}
