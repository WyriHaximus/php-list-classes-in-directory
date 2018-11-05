<?php declare(strict_types=1);

namespace WyriHaximus;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

/**
 * get a list of all classes in the given directories.
 *
 * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
 *
 * @param string[] $directories
 *
 * @return iterable
 */
function listClassesInDirectories(string ...$directories): iterable
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
 * get a list of all classes in the given directory.
 *
 * @param string $directory
 *
 * @return iterable
 */
function listClassesInDirectory(string $directory): iterable
{
    yield from listClassesInDirectories($directory);
}

/**
 * get a list of all classes in the given file.
 *
 * @param string $file
 *
 * @return iterable
 */
function listClassesInFile(string $file): iterable
{
    $sourceLocator = new AggregateSourceLocator([
        new SingleFileSourceLocator(
            $file,
            (new BetterReflection())->astLocator()
        ),
        // ↓ required to autoload parent classes/interface from another directory (e.g. /vendor)
        new AutoloadSourceLocator((new BetterReflection())->astLocator()),
    ]);

    foreach ((new ClassReflector($sourceLocator))->getAllClasses() as $class) {
        yield $class->getName();
    }
}

/**
 * get a list of all classes in the given files.
 *
 * @param string[] $files
 *
 * @return iterable
 */
function listClassesInFiles(string ...$files): iterable
{
    foreach ($files as $file) {
        foreach (listClassesInFile($file) as $class) {
            yield $class;
        }
    }
}

function listInstantiatableClassesInDirectories(string ...$directories): iterable
{
    $iterator = listClassesInDirectories(...$directories);

    return new class($iterator) extends \FilterIterator {
        public function accept(): bool
        {
            $className = $this->getInnerIterator()->current();
            try {
                $reflectionClass = ReflectionClass::createFromName($className);

                return $reflectionClass->isInstantiable();
            } catch (IdentifierNotFound $exception) {
                return false;
            }
        }
    };
}

function listInstantiatableClassesInDirectory(string $directory): iterable
{
    yield from listInstantiatableClassesInDirectories($directory);
}

function listNonInstantiatableClassesInDirectories(string ...$directories): iterable
{
    $iterator = listClassesInDirectories(...$directories);

    return new class($iterator) extends \FilterIterator {
        public function accept(): bool
        {
            $className = $this->getInnerIterator()->current();
            try {
                $reflectionClass = ReflectionClass::createFromName($className);

                return $reflectionClass->isInstantiable() === false;
            } catch (IdentifierNotFound $exception) {
                return true;
            }
        }
    };
}

function listNonInstantiatableClassesInDirectory(string $directory): iterable
{
    yield from listNonInstantiatableClassesInDirectories($directory);
}
