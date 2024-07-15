<?php

declare(strict_types=1);

namespace WyriHaximus;

use ArrayIterator;
use FilterIterator;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

use function array_values;
use function class_exists;
use function is_string;

use const WyriHaximus\Constants\Boolean\FALSE_;
use const WyriHaximus\Constants\Boolean\TRUE_;

/**
 * get a list of all classes in the given directories.
 *
 * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
 *
 * @return iterable<string>
 */
function listClassesInDirectories(string ...$directories): iterable
{
    $sourceLocator = new AggregateSourceLocator([
        new DirectoriesSourceLocator(
            array_values($directories),
            (new BetterReflection())->astLocator(),
        ),
        // ↓ required to autoload parent classes/interface from another directory than /src (e.g. /vendor)
        new AutoloadSourceLocator((new BetterReflection())->astLocator()),
    ]);

    foreach (listClassesInSourceLocator($sourceLocator) as $class) {
        yield $class->getName();
    }
}

/**
 * get a list of all classes in the given directory.
 *
 * @return iterable<string>
 */
function listClassesInDirectory(string $directory): iterable
{
    yield from listClassesInDirectories($directory);
}

/**
 * get a list of all classes in the given file.
 *
 * @param non-empty-string $file
 *
 * @return iterable<string>
 */
function listClassesInFile(string $file): iterable
{
    $sourceLocator = new AggregateSourceLocator([
        new SingleFileSourceLocator(
            $file,
            (new BetterReflection())->astLocator(),
        ),
        // ↓ required to autoload parent classes/interface from another directory (e.g. /vendor)
        new AutoloadSourceLocator((new BetterReflection())->astLocator()),
    ]);

    foreach (listClassesInSourceLocator($sourceLocator) as $class) {
        yield $class->getName();
    }
}

/**
 * get a list of all classes in the given files.
 *
 * @param non-empty-string ...$files
 *
 * @return iterable<string>
 */
function listClassesInFiles(string ...$files): iterable
{
    foreach ($files as $file) {
        foreach (listClassesInFile($file) as $class) {
            yield $class;
        }
    }
}

/** @return iterable<string> */
function listInstantiatableClassesInDirectories(string ...$directories): iterable
{
    $iterator = listClassesInDirectories(...$directories);

    /**
     * @psalm-suppress MissingTemplateParam
     * @psalm-suppress InvalidOperand
     */
    return new class (new ArrayIterator([...$iterator])) extends FilterIterator {
        public function accept(): bool
        {
            $className = $this->getInnerIterator()->current();
            if (is_string($className) === FALSE_) {
                return FALSE_;
            }

            try {
                $reflectionClass = ReflectionClass::createFromName($className);

                return $reflectionClass->isInstantiable();
            } catch (IdentifierNotFound) {
                return FALSE_;
            }
        }
    };
}

/** @return iterable<string> */
function listInstantiatableClassesInDirectory(string $directory): iterable
{
    yield from listInstantiatableClassesInDirectories($directory);
}

/** @return iterable<string> */
function listNonInstantiatableClassesInDirectories(string ...$directories): iterable
{
    $iterator = listClassesInDirectories(...$directories);

    /**
     * @psalm-suppress MissingTemplateParam
     * @psalm-suppress InvalidOperand
     */
    return new class (new ArrayIterator([...$iterator])) extends FilterIterator {
        public function accept(): bool
        {
            $className = $this->getInnerIterator()->current();
            if (is_string($className) === FALSE_) {
                return FALSE_;
            }

            try {
                $reflectionClass = ReflectionClass::createFromName($className);

                return $reflectionClass->isInstantiable() === FALSE_;
            } catch (IdentifierNotFound) {
                return TRUE_;
            }
        }
    };
}

/** @return iterable<string> */
function listNonInstantiatableClassesInDirectory(string $directory): iterable
{
    yield from listNonInstantiatableClassesInDirectories($directory);
}

/**
 * @internal
 *
 * @return iterable<ReflectionClass>
 */
function listClassesInSourceLocator(AggregateSourceLocator $sourceLocator): iterable
{
    /** @psalm-suppress UndefinedClass */
    yield from class_exists(ClassReflector::class) ? (new ClassReflector($sourceLocator))->getAllClasses() : (new DefaultReflector($sourceLocator))->reflectAllClasses();
}
