<?php

$rules = [
    'align_multiline_comment' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'multiline_whitespace_before_semicolons' => true,
    'no_leading_namespace_whitespace' => true,
    'braces' => true,
    'cast_spaces' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'constant_case' => true,
    'declare_equal_normalize' => true,
    'elseif' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'function_declaration' => true,
    'function_typehint_space' => true,
    'global_namespace_import' => ['import_classes' => true],
    'linebreak_after_opening_tag' => true,
    'lowercase_cast' => true,
    'method_chaining_indentation' => true,
    'multiline_comment_opening_closing' => true,
    'no_empty_comment' => true,
    'no_closing_tag' => true,
    'no_blank_lines_before_namespace' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_empty_statement' => true,
    'no_short_bool_cast' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    'group_import' => true
];

$finder = Symfony\Component\Finder\Finder::create()
    ->exclude("vendor")
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRules($rules)
    ->setFinder($finder);
