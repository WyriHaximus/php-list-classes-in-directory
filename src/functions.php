<?php declare(strict_types=1);

namespace WyriHaximus;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

/**
 * get a list of all classes in the given direcotories.
 *
 * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
 * 
 * @param array<int, string> $directories
 *
 * @return iterable
 */
function listClassesInDirecories(string ...$directories): iterable
{
    $sourceLocator = new AggregateSourceLocator([
        new DirectoriesSourceLocator(
            $directories,
            (new BetterReflection())->astLocator()
        ),
        // ↓ required to autoload parent classes/interface from another directory than /src (e.g. /vendor)
        new AutoloadSourceLocator((new BetterReflection())->astLocator()),
    ]);

    foreach ((new ClassReflector($sourceLocator))->getAllClasses() as $class) {
        yield $class->getName();
    }
}

/**
 * get a list of all classes in the given direcotory.
 *
 * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
 * 
 * @param string $directory
 *
 * @return iterable
 */
function listClassesInDirectory(string $directory): iterable
{
    yield from listClassesInDirecories($directory);
}
