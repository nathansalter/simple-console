<?php

namespace Console\Command; 

class Ls extends AbstractCommand
{
  public function getAlias()
  {
    return 'list'; 
  }

  public function getCommand()
  {
    return 'ls'; 
  }
}

