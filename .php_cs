<?php

$finder = PhpCsFixer\Finder::create()
    ->in('./src')
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'no_unused_imports' => true,
        'strict_param' => true,
        'line_ending' => true,
        'full_opening_tag' => true,
        'indentation_type' => true,
    ])
    ->setIndent("\t")
    ->setLineEnding("\r\n")
    ->setFinder($finder);