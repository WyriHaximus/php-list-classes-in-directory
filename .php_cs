<?php declare(strict_types=1);

use ApiClients\Tools\CsFixerConfig\PhpCsFixerConfig;
use PhpCsFixer\Config;

return (function (): Config
{
    $paths = [
        __DIR__ . DIRECTORY_SEPARATOR . 'src',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests',
        __DIR__ . DIRECTORY_SEPARATOR . 'test-app',
        __DIR__ . DIRECTORY_SEPARATOR . 'test-classes',
    ];

    return PhpCsFixerConfig::create()
        ->setFinder(
            PhpCsFixer\Finder::create()
                ->in($paths)
                ->append($paths)
        )
        ->setUsingCache(false)
    ;
})();
