<?php

namespace Console\Command; 

class Quit extends AbstractCommand
{
  public function getAlias()
  {
    return 'quit()'; 
  }

  public function getCommand()
  {
    return 'exit();'; 
  }
}

