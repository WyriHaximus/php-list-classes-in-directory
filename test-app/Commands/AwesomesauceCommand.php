<?php

namespace Test\App\Commands;

use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

/**
 * @Handler("Test\App\Handlers\AwesomesauceHandler")
 */
class AwesomesauceCommand
{
    /**
     * @var string
     */
    private $value;

    /**
     * AwesomesauceCommand constructor.
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}