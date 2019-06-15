<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create();
$finder->in( __DIR__ . '/src');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'psr4' => true,
        'declare_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'method_argument_space' => [
            'ensure_fully_multiline' => true,
        ],
        'modernize_types_casting' => true,
        'no_useless_return' => true,
        'ordered_imports' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => false,
        'phpdoc_inline_tag' => false,
        'phpdoc_no_useless_inheritdoc' => false,
        'phpdoc_order' => true,
        'ternary_to_null_coalescing' => true,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
