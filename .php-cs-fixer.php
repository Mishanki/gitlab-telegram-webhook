<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create();

$finder = Symfony\Component\Finder\Finder::create()
    ->in([
        __DIR__.'/app',
    ])
    ->exclude([
        __DIR__.'/storage',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PHP82Migration' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'blank_line_before_statement' => true,
        'comment_to_phpdoc' => false,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'phpdoc_add_missing_param_annotation' => true,
        'no_superfluous_phpdoc_tags' => false,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'no_unused_imports' => true,
        'single_line_comment_style' => false,
        'strict_comparison' => false,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
    ])
    ->setFinder($finder)
;
