<?php

namespace Console\Tests\Console\Runner; 

class TestExitRunner extends \Console\Runner\AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    return false; 
  }
}

