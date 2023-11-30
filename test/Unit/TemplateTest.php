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
use Ergebnis\License\Template;
use Ergebnis\License\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Template
 *
 * @uses \Ergebnis\License\Exception\InvalidFile
 * @uses \Ergebnis\License\Exception\InvalidReplacements
 */
final class TemplateTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromStringReturnsTemplate(): void
    {
        $faker = self::faker();

        $when = $faker->dateTime()->format('Y');
        $who = $faker->name();

        $template = Template::fromString(
            <<<'EOF'
Ah!

This was done in <when> by <who>.

Who would have thought?

EOF
        );

        $expected = <<<EOF
Ah!

This was done in {$when} by {$who}.

Who would have thought?

EOF;

        self::assertSame($expected, $template->toString([
            '<when>' => $when,
            '<who>' => $who,
        ]));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty()
     */
    public function testFromFileRejectsBlankOrEmptyFileName(string $name): void
    {
        $this->expectException(Exception\InvalidFile::class);

        Template::fromFile($name);
    }

    public function testFromFileRejectsNonExistentFile(): void
    {
        $this->expectException(Exception\InvalidFile::class);

        Template::fromFile(__DIR__ . '/does-not-exist.txt');
    }

    public function testFromFileRejectsFileReferencingDirectory(): void
    {
        $this->expectException(Exception\InvalidFile::class);

        Template::fromFile(__DIR__);
    }

    public function testFromFileReturnsTemplate(): void
    {
        $faker = self::faker();

        $when = $faker->dateTime()->format('Y');
        $who = $faker->name();

        $template = Template::fromFile(__DIR__ . '/../Fixture/Template/template.txt');

        $expected = <<<EOF
Ah!

This was done in {$when} by {$who}.

Who would have thought?

EOF;

        self::assertSame($expected, $template->toString([
            '<when>' => $when,
            '<who>' => $who,
        ]));
    }

    public function testToStringRejectsReplacementsWithInvalidKeys(): void
    {
        $faker = self::faker();

        $replacements = [
            $faker->numberBetween(1) => $faker->word(),
        ];

        $template = Template::fromString('');

        $this->expectException(Exception\InvalidReplacements::class);

        $template->toString($replacements);
    }

    /**
     * @dataProvider provideReplacementsWithInvalidValues
     */
    public function testToStringRejectsReplacementsWithInvalidValues(array $replacements): void
    {
        $template = Template::fromString('');

        $this->expectException(Exception\InvalidReplacements::class);

        $template->toString($replacements);
    }

    /**
     * @return \Generator<string, array{0: array<string, mixed>}>
     */
    public static function provideReplacementsWithInvalidValues(): iterable
    {
        $faker = self::faker();

        $values = [
            'array' => $faker->words(),
            'boolean-false' => false,
            'boolean-true' => true,
            'float' => $faker->randomFloat(2, 1),
            'int' => $faker->numberBetween(1),
            'null' => null,
            'object' => new \stdClass(),
            'resource' => \fopen(__FILE__, 'rb'),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                [
                    'foo' => $value,
                ],
            ];
        }
    }
}
