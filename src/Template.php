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

namespace Ergebnis\License;

final class Template
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @param array $replacements
     *
     * @throws Exception\InvalidReplacements
     *
     * @return string
     */
    public function toString(array $replacements): string
    {
        $invalidKeys = \array_filter(\array_keys($replacements), static function ($key): bool {
            return !\is_string($key);
        });

        if ([] !== $invalidKeys) {
            throw Exception\InvalidReplacements::keys();
        }

        $invalidValues = \array_filter($replacements, static function ($value): bool {
            return !\is_string($value);
        });

        if ([] !== $invalidValues) {
            throw Exception\InvalidReplacements::values();
        }

        return \str_replace(
            \array_keys($replacements),
            $replacements,
            $this->value
        );
    }
}
