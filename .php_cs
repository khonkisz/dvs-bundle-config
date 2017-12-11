<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@PSR1' => true,
        'blank_line_before_return' => true,
        'yoda_style' => null,
        'no_unused_imports' => false
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.DIRECTORY_SEPARATOR.'src')
            ->in(__DIR__.DIRECTORY_SEPARATOR.'spec')
    )
    ->setRiskyAllowed(true)
    ->setUsingCache(true);

