<?php

namespace Console\Tests\Console\Runner; 

class TestRunner extends \Console\Runner\AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    return $command->getAlias() . (count($command->getArguments()) ? ' ' . join(' ', $command->getArguments()) : ''); 
  }
}

