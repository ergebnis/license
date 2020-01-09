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
use Ergebnis\License\Template;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Template
 *
 * @uses \Ergebnis\License\Exception\InvalidReplacements
 */
final class TemplateTest extends Framework\TestCase
{
    use Helper;

    public function testCreateReturnsTemplate(): void
    {
        $faker = self::faker();

        $when = $faker->dateTime->format('Y');
        $who = $faker->name;

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
     * @dataProvider provideReplacementsWithInvalidKeys
     *
     * @param array $replacements
     */
    public function testToStringRejectsReplacementsWithInvalidKeys(array $replacements): void
    {
        $template = Template::fromString('');

        $this->expectException(Exception\InvalidReplacements::class);

        $template->toString($replacements);
    }

    public function provideReplacementsWithInvalidKeys(): \Generator
    {
        $faker = self::faker();

        $values = [
            'float' => $faker->randomFloat(2, 1),
            'int' => $faker->numberBetween(1),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                [
                    $value => 'foo',
                ],
            ];
        }
    }

    /**
     * @dataProvider provideReplacementsWithInvalidValues
     *
     * @param array $replacements
     */
    public function testToStringRejectsReplacementsWithInvalidValues(array $replacements): void
    {
        $template = Template::fromString('');

        $this->expectException(Exception\InvalidReplacements::class);

        $template->toString($replacements);
    }

    public function provideReplacementsWithInvalidValues(): \Generator
    {
        $faker = self::faker();

        $values = [
            'array' => $faker->words,
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
