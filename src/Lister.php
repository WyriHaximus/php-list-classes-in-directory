<?php

declare(strict_types=1);

namespace WyriHaximus;

use ArrayIterator;
use FilterIterator;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

use function array_values;
use function is_string;

final readonly class Lister
{
    /**
     * get a list of all classes in the given directories.
     *
     * Based on: https://github.com/Roave/BetterReflection/blob/396a07c9d276cb9ffba581b24b2dadbb542d542e/demo/parsing-whole-directory/example2.php.
     *
     * @return iterable<string>
     */
    public static function classesInDirectories(string ...$directories): iterable
    {
        $sourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator(
                array_values($directories),
                (new BetterReflection())->astLocator(),
            ),
            // ↓ required to autoload parent classes/interface from another directory than /src (e.g. /vendor)
            new AutoloadSourceLocator((new BetterReflection())->astLocator()),
        ]);

        foreach (self::classesInSourceLocator($sourceLocator) as $class) {
            yield $class->getName();
        }
    }

    /**
     * get a list of all classes in the given directory.
     *
     * @return iterable<string>
     */
    public static function classesInDirectory(string $directory): iterable
    {
        yield from self::classesInDirectories($directory);
    }

    /**
     * get a list of all classes in the given file.
     *
     * @param non-empty-string $file
     *
     * @return iterable<string>
     */
    public static function classesInFile(string $file): iterable
    {
        $sourceLocator = new AggregateSourceLocator([
            new SingleFileSourceLocator(
                $file,
                (new BetterReflection())->astLocator(),
            ),
            // ↓ required to autoload parent classes/interface from another directory (e.g. /vendor)
            new AutoloadSourceLocator((new BetterReflection())->astLocator()),
        ]);

        foreach (self::classesInSourceLocator($sourceLocator) as $class) {
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
    public static function classesInFiles(string ...$files): iterable
    {
        foreach ($files as $file) {
            foreach (self::classesInFile($file) as $class) {
                yield $class;
            }
        }
    }

    /** @return iterable<string> */
    public static function instantiatableClassesInDirectories(string ...$directories): iterable
    {
        $iterator = self::classesInDirectories(...$directories);

        /**
         * @psalm-suppress MissingTemplateParam
         * @psalm-suppress InvalidOperand
         */
        return new class (new ArrayIterator([...$iterator])) extends FilterIterator {
            private const DOES_NOT_ACCEPT_CLASS = false;

            public function accept(): bool
            {
                $className = $this->getInnerIterator()->current();
                if (! is_string($className)) {
                    return self::DOES_NOT_ACCEPT_CLASS;
                }

                try {
                    $reflectionClass = ReflectionClass::createFromName($className);

                    return $reflectionClass->isInstantiable();
                } catch (IdentifierNotFound) {
                    return self::DOES_NOT_ACCEPT_CLASS;
                }
            }
        };
    }

    /** @return iterable<string> */
    public static function instantiatableClassesInDirectory(string $directory): iterable
    {
        yield from self::instantiatableClassesInDirectories($directory);
    }

    /** @return iterable<string> */
    public static function nonInstantiatableClassesInDirectories(string ...$directories): iterable
    {
        $iterator = self::classesInDirectories(...$directories);

        /**
         * @psalm-suppress MissingTemplateParam
         * @psalm-suppress InvalidOperand
         */
        return new class (new ArrayIterator([...$iterator])) extends FilterIterator {
            private const DOES_NOT_ACCEPT_CLASS = false;

            public function accept(): bool
            {
                $className = $this->getInnerIterator()->current();
                if (! is_string($className)) {
                    return self::DOES_NOT_ACCEPT_CLASS;
                }

                try {
                    $reflectionClass = ReflectionClass::createFromName($className);

                    return $reflectionClass->isInstantiable() === self::DOES_NOT_ACCEPT_CLASS;
                } catch (IdentifierNotFound) {
                    return self::DOES_NOT_ACCEPT_CLASS;
                }
            }
        };
    }

    /** @return iterable<string> */
    public static function nonInstantiatableClassesInDirectory(string $directory): iterable
    {
        yield from self::nonInstantiatableClassesInDirectories($directory);
    }

    /**
     * @internal
     *
     * @return iterable<ReflectionClass>
     */
    public static function classesInSourceLocator(AggregateSourceLocator $sourceLocator): iterable
    {
        /** @psalm-suppress UndefinedClass */
        yield from (new DefaultReflector($sourceLocator))->reflectAllClasses();
    }
}
