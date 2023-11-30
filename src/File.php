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

namespace Ergebnis\License;

final class File
{
    private function __construct(
        private string $name,
        private Template $template,
        private Period $period,
        private Holder $holder,
    ) {
    }

    /**
     * @throws Exception\InvalidFile
     */
    public static function create(
        string $name,
        Template $template,
        Period $period,
        Holder $holder,
    ): self {
        if ('' === \trim($name)) {
            throw Exception\InvalidFile::emptyFileName();
        }

        return new self(
            $name,
            $template,
            $period,
            $holder,
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function save(): void
    {
        \file_put_contents($this->name, $this->template->toString([
            '<holder>' => $this->holder->toString(),
            '<range>' => $this->period->toString(),
        ]));
    }
}
