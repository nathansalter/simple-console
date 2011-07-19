<?php

namespace Console\Command; 

abstract class AbstractCommand
{
  protected
    $arguments = array(); 

  abstract public function getAlias(); 
  abstract public function getCommand(); 
  
  public function addArgument($argument)
  {
    $this->arguments[] = $argument; 
  }

  public function getArguments()
  {
    return $this->arguments; 
  }

  public function clearArguments()
  {
    $this->arguments = array(); 
  }
}

