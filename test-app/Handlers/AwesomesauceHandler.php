<?php

namespace Test\App\Handlers;

use Test\App\Commands\AwesomesauceCommand;

class AwesomesauceHandler
{
    /**
     * @param AwesomesauceCommand $command
     * @return string
     */
    public function handler(AwesomesauceCommand $command)
    {
        return $command->getValue();
    }
}