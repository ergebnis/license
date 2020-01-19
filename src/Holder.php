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

namespace Ergebnis\License;

use Ergebnis\License\Exception\InvalidHolder;

final class Holder
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @throws Exception\InvalidHolder
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        if (1 === \preg_match('/^(\r\n|\n|\r)/', $value)) {
            throw InvalidHolder::multiline();
        }

        $trimmed = \trim($value);

        if ('' === $trimmed) {
            throw Exception\InvalidHolder::blankOrEmpty();
        }

        return new self($trimmed);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
