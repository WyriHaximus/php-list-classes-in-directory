<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use Test\App\Commands\AwesomesauceCommand;
use Test\App\Foo\Bar\Bar;
use Test\App\Foo\Bar\Foo;
use Test\App\Handlers\AwesomesauceHandler;
use Test\Classes\InstantiatableClass;
use Test\Classes\NonInstantiatableClass;
use WyriHaximus\TestUtilities\TestCase;

use function dirname;
use function Safe\sort;
use function WyriHaximus\listClassesInDirectories;
use function WyriHaximus\listClassesInDirectory;
use function WyriHaximus\listClassesInFile;
use function WyriHaximus\listClassesInFiles;
use function WyriHaximus\listInstantiatableClassesInDirectory;
use function WyriHaximus\listNonInstantiatableClassesInDirectory;

use const DIRECTORY_SEPARATOR;
use const SORT_NATURAL;

/**
 * @internal
 */
final class FunctionalTest extends TestCase
{
    public function testListClassesInDirectory(): void
    {
        $classes = [...listClassesInDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR)];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            AwesomesauceCommand::class,
            Bar::class,
            Foo::class,
            AwesomesauceHandler::class,
        ], $classes);
    }

    public function testListClassesInDirectories(): void
    {
        $app = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;

        $classes = [...listClassesInDirectories($app . 'Handlers', $app . 'Commands')];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            AwesomesauceCommand::class,
            AwesomesauceHandler::class,
        ], $classes);
    }

    public function testListClassesInFile(): void
    {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR . 'Foo' . DIRECTORY_SEPARATOR . 'Bar';

        $classes = [...listClassesInFile($path . DIRECTORY_SEPARATOR . 'BarAndFoo.php')];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            Bar::class,
            Foo::class,
        ], $classes);
    }

    public function testListClassesInFiles(): void
    {
        $app     = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;
        $foobar  = $app . 'Foo' . DIRECTORY_SEPARATOR . 'Bar' . DIRECTORY_SEPARATOR . 'BarAndFoo.php';
        $handler = $app . 'Handlers' . DIRECTORY_SEPARATOR . 'AwesomesauceHandler.php';

        $classes = [...listClassesInFiles($foobar, $handler)];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            Bar::class,
            Foo::class,
            AwesomesauceHandler::class,
        ], $classes);
    }

    public function testListInstantiatableClassesInDirectory(): void
    {
        $directory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-classes' . DIRECTORY_SEPARATOR;
        $classes   = [...listInstantiatableClassesInDirectory($directory)];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            InstantiatableClass::class,
        ], $classes);
    }

    public function testListNonInstantiatableClassesInDirectory(): void
    {
        $directory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-classes' . DIRECTORY_SEPARATOR;
        $classes   = [...listNonInstantiatableClassesInDirectory($directory)];
        sort($classes, SORT_NATURAL);

        self::assertSame([
            NonInstantiatableClass::class,
        ], $classes);
    }
}
