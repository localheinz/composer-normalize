<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/composer-normalize
 */
use Localheinz\PhpCsFixer\Config;

$header = <<<'EOF'
Copyright (c) 2018 Andreas Möller

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.

@see https://github.com/localheinz/composer-normalize
EOF;

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71($header));

$config->getFinder()
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->exclude([
        '.github',
        '.php-cs-fixer',
        '.phpstan',
        '.travis',
    ])
    ->name('.php_cs');

$directory = \getenv('TRAVIS') ? \getenv('HOME') : __DIR__;

$config->setCacheFile($directory . '/.php-cs-fixer/.php_cs.cache');

return $config;
