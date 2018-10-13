<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Handlers\AwesomesauceHandler;
use function dirname;
use function WyriHaximus\listClassesInDirectories;
use function WyriHaximus\listClassesInDirectory;

final class FunctionalTest extends TestCase
{
    public function testListClassesInDirectory(): void
    {
        $classes = iterator_to_array(listClassesInDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR));
        self::assertSame([
            AwesomesauceHandler::class,
            AwesomesauceCommand::class,
        ], $classes);
    }

    public function testListClassesInDirectories(): void
    {
        $app = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;

        $classes = iterator_to_array(listClassesInDirectories($app . 'Handlers', $app . 'Commands'));
        self::assertSame([
            AwesomesauceHandler::class,
            AwesomesauceCommand::class,
        ], $classes);
    }
}
