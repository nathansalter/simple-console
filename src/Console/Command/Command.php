<?php

namespace Console\Command; 

class Command extends AbstractCommand
{
  protected
    $command, 
    $alias; 
  
  public function __construct($command, $alias)
  {
    $this->command = $command; 
    $this->alias = $alias; 
  }

  public function setCommand($command)
  {
    if (empty($command))
    {
      throw new InvalidArgumentException('The command must not be empty!'); 
    }

    $this->command = $command; 
  }

  public function getCommand()
  {
    return $this->command; 
  }

  public function setAlias($alias)
  {
    if (empty($alias))
    {
      throw new InvalidArgumentException('The alias must not be empty!'); 
    }

    $this->alias = $alias; 
  }

  public function getAlias()
  {
    return $this->alias; 
  }
}

