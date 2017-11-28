<?php

$header = <<<EOF
This file is part of the Laravel Multiselect package.

(c) Andre Chalom <andrechalom@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
            '@PSR1' => true,
            '@PSR2' => true,
            '@Symfony' => true,
            'header_comment' => [ 'header' => $header ],
            'no_extra_consecutive_blank_lines' => true,
            'no_unused_imports' => true,
            'standardize_not_equals' => true,
            'ternary_operator_spaces' => true,
            'trailing_comma_in_multiline_array' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
    )
;
