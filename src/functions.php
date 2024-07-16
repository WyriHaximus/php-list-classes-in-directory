<?php

declare(strict_types=1);

namespace WyriHaximus;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;

/**
 * get a list of all classes in the given directories.
 *
 * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
 *
 * @return iterable<string>
 */
function listClassesInDirectories(string ...$directories): iterable
{
    return Lister::classesInDirectories(...$directories);
}

/**
 * get a list of all classes in the given directory.
 *
 * @return iterable<string>
 */
function listClassesInDirectory(string $directory): iterable
{
    return Lister::classesInDirectory($directory);
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
    return Lister::classesInFile($file);
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
    return Lister::classesInFiles(...$files);
}

/** @return iterable<string> */
function listInstantiatableClassesInDirectories(string ...$directories): iterable
{
    return Lister::instantiatableClassesInDirectories(...$directories);
}

/** @return iterable<string> */
function listInstantiatableClassesInDirectory(string $directory): iterable
{
    return Lister::instantiatableClassesInDirectories($directory);
}

/** @return iterable<string> */
function listNonInstantiatableClassesInDirectories(string ...$directories): iterable
{
    return Lister::nonInstantiatableClassesInDirectories(...$directories);
}

/** @return iterable<string> */
function listNonInstantiatableClassesInDirectory(string $directory): iterable
{
    return Lister::nonInstantiatableClassesInDirectory($directory);
}

/**
 * @internal
 *
 * @return iterable<ReflectionClass>
 */
function listClassesInSourceLocator(AggregateSourceLocator $sourceLocator): iterable
{
    return Lister::classesInSourceLocator($sourceLocator);
}
