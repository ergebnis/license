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

namespace Ergebnis\License\Type;

use Ergebnis\License\File;
use Ergebnis\License\Header;
use Ergebnis\License\Holder;
use Ergebnis\License\Period;
use Ergebnis\License\Template;
use Ergebnis\License\Url;

final class MIT
{
    private File $file;
    private Header $header;

    private function __construct(
        string $name,
        Template $fileTemplate,
        Template $headerTemplate,
        Period $period,
        Holder $holder,
        Url $url
    ) {
        $file = File::create(
            $name,
            $fileTemplate,
            $period,
            $holder,
        );

        $header = Header::createWithReferenceToLicenseFile(
            $headerTemplate,
            $period,
            $holder,
            $file,
            $url,
        );

        $this->file = $file;
        $this->header = $header;
    }

    public static function markdown(
        string $name,
        Period $period,
        Holder $holder,
        Url $url
    ): self {
        return new self(
            $name,
            Template::fromFile(__DIR__ . '/../../resource/license/MIT.md'),
            Template::fromFile(__DIR__ . '/../../resource/header/with-reference-to-license-file.txt'),
            $period,
            $holder,
            $url,
        );
    }

    public static function text(
        string $name,
        Period $period,
        Holder $holder,
        Url $url
    ): self {
        return new self(
            $name,
            Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt'),
            Template::fromFile(__DIR__ . '/../../resource/header/with-reference-to-license-file.txt'),
            $period,
            $holder,
            $url,
        );
    }

    public function save(): void
    {
        $this->file->save();
    }

    public function header(): string
    {
        return $this->header->toString();
    }
}
