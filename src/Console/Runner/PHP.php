<?php

namespace Console\Runner; 

class PHP extends AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    eval($command->getCommand()); 
  }
}

