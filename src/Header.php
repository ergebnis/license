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

namespace Ergebnis\License;

final class Header
{
    private $template;
    private $period;
    private $holder;
    private $file;
    private $url;

    private function __construct(
        Template $template,
        Period $period,
        Holder $holder,
        File $file,
        Url $url
    ) {
        $this->template = $template;
        $this->period = $period;
        $this->holder = $holder;
        $this->file = $file;
        $this->url = $url;
    }

    public static function create(
        Template $template,
        Period $period,
        Holder $holder,
        File $file,
        Url $url
    ): self {
        return new self(
            $template,
            $period,
            $holder,
            $file,
            $url,
        );
    }

    public function toString(): string
    {
        return $this->template->toString([
            '<file>' => \basename($this->file->name()),
            '<holder>' => $this->holder->toString(),
            '<range>' => $this->period->toString(),
            '<url>' => $this->url->toString(),
        ]);
    }
}
