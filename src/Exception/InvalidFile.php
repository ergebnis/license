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

namespace Ergebnis\License\Exception;

final class InvalidFile extends \InvalidArgumentException implements Exception
{
    public static function emptyFileName(): self
    {
        return new self('File name can not be empty.');
    }

    public static function doesNotExist(string $name): self
    {
        return new self(\sprintf(
            'A file with name "%s" does not exist.',
            $name
        ));
    }

    public static function canNotBeRead(string $name): self
    {
        return new self(\sprintf(
            'File with name "%s" can not be read.',
            $name
        ));
    }
}
