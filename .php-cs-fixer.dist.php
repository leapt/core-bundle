<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_unneeded_final_method' => false,
        'dir_constant' => true,
        'modernize_types_casting' => true,
        'echo_tag_syntax' => ['format' => 'short'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized']],
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_construct' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'yoda_style' => [
            'always_move_variable' => true,
            'equal' => true,
            'identical' => true,
            'less_and_greater' => true,
        ],
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache')
;
