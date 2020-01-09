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

use Ergebnis\License;
use Ergebnis\PhpCsFixer\Config;

$holder = License\Holder::fromString('Andreas Möller');
$range = License\Range::since(
    License\Year::fromString('2020'),
    new \DateTimeZone('UTC')
);
$url = License\Url::fromString('https://github.com/ergebnis/license');

$headerTemplate = License\Template::fromString(
    <<<'EOF'
Copyright (c) <range> <holder>

For the full copyright and license information, please view
the <file> file that was distributed with this source code.

@see <url>
EOF
);

$fileTemplate = License\Template::fromString(
    <<<'EOF'
The MIT License (MIT)

Copyright (c) <range> <holder>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

EOF
);

$file = __DIR__ . '/LICENSE';

\file_put_contents($file, $fileTemplate->toString([
    '<holder>' => $holder->toString(),
    '<range>' => $range->toString(),
]));

$header = $headerTemplate->toString([
    '<file>' => \basename($file),
    '<holder>' => $holder->toString(),
    '<range>' => $range->toString(),
    '<url>' => $url->toString(),
]);

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71($header));

$config->getFinder()
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->exclude([
        '.build',
        '.dependabot',
        '.github',
    ])
    ->name('.php_cs');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php_cs.cache');

return $config;
