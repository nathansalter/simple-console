<?php

namespace Console\Runner; 

class Shell extends AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    $output = array(); 
    exec($command->getCommand() . ' ' . join(' ', $command->getArguments()), $output);

    return join("\n", $output) . "\n"; 
  }
}

