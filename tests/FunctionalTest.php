<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Foo\Bar\Bar;
use Test\App\Foo\Bar\Foo;
use Test\App\Handlers\AwesomesauceHandler;
use const DIRECTORY_SEPARATOR;
use function dirname;
use function WyriHaximus\listClassesInDirectories;
use function WyriHaximus\listClassesInDirectory;
use function WyriHaximus\listClassesInFile;
use function WyriHaximus\listClassesInFiles;

final class FunctionalTest extends TestCase
{
    public function testListClassesInDirectory(): void
    {
        $classes = iterator_to_array(listClassesInDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR));
        self::assertSame([
            AwesomesauceCommand::class,
            AwesomesauceHandler::class,
            Bar::class,
            Foo::class,
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

    public function testListClassesInFile(): void
    {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar';

        $classes = iterator_to_array(listClassesInFile($path . DIRECTORY_SEPARATOR . 'BarAndFoo.php'));
        self::assertSame([
            Bar::class,
            Foo::class,
        ], $classes);
    }

    public function testListClassesInFiles(): void
    {
        $app = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;
        $foobar = $app . 'Foo' . DIRECTORY_SEPARATOR . 'Bar'. DIRECTORY_SEPARATOR . 'BarAndFoo.php';
        $handler = $app . 'Handlers' . DIRECTORY_SEPARATOR . 'AwesomesauceHandler.php';

        $classes = iterator_to_array(listClassesInFiles($foobar, $handler));
        self::assertSame([
            Bar::class,
            Foo::class,
            AwesomesauceHandler::class,
        ], $classes);
    }
}
