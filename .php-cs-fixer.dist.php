<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/app'
    ])
    ->notPath([
        'Http/Middleware',
        'Providers',
        'Http/Kernel.php',
        'Console/Kernel.php',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'echo_tag_syntax' => ['format' => 'short', 'shorten_simple_statements_only' => true],
        'phpdoc_to_comment' => false,
        'single_line_throw' => false,
        'declare_strict_types' => true,
        'concat_space' => ['spacing' => 'one'],
        'void_return' => true
    ])
    ->setFinder($finder);
