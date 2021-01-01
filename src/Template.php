<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
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
     * @throws Exception\InvalidFile
     */
    public static function fromFile(string $name): self
    {
        if ('' === \trim($name)) {
            throw Exception\InvalidFile::emptyFileName();
        }

        if (!\file_exists($name)) {
            throw Exception\InvalidFile::doesNotExist($name);
        }

        if (!\is_file($name)) {
            throw Exception\InvalidFile::doesNotExist($name);
        }

        if (!\is_readable($name)) {
            throw Exception\InvalidFile::canNotBeRead($name);
        }

        $contents = \file_get_contents($name);

        if (false === $contents) {
            throw Exception\InvalidFile::canNotBeRead($name);
        }

        return new self($contents);
    }

    /**
     * @param array<string, string> $replacements
     *
     * @throws Exception\InvalidReplacements
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
