<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License;

final class Header
{
    private Template $template;
    private Url $url;
    private ?File $file;
    private Holder $holder;
    private Period $period;

    private function __construct(
        Template $template,
        Period $period,
        Holder $holder,
        ?File $file,
        Url $url
    ) {
        $this->period = $period;
        $this->holder = $holder;
        $this->file = $file;
        $this->url = $url;
        $this->template = $template;
    }

    public static function createWithReferenceToLicenseFile(
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

    public static function createWithoutReferenceToLicenseFile(
        Template $template,
        Period $range,
        Holder $holder,
        Url $url
    ): self {
        return new self(
            $template,
            $range,
            $holder,
            null,
            $url,
        );
    }

    public function toString(): string
    {
        if (!$this->file instanceof File) {
            return $this->template->toString([
                '<holder>' => $this->holder->toString(),
                '<range>' => $this->period->toString(),
                '<url>' => $this->url->toString(),
            ]);
        }

        return $this->template->toString([
            '<file>' => \basename($this->file->name()),
            '<holder>' => $this->holder->toString(),
            '<range>' => $this->period->toString(),
            '<url>' => $this->url->toString(),
        ]);
    }
}
