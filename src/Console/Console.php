<?php

namespace Console; 

class Console
{
  protected
    $defaultRunner, 
    $commands = array(); 

  public function __construct(Runner\AbstractRunner $runner = null)
  {
    $this->runner = null === $runner ? new Runner\NullRunner() : $runner; 

    $this->registerCommand(new Command\Ls(), new Runner\Shell());
    $this->registerCommand(new Command\Quit(), new Runner\PHP());  
  }

  public function registerCommand(Command\AbstractCommand $command, Runner\AbstractRunner $runner = null)
  {
    $this->commands[$command->getAlias()] = array($command, $runner); 
  }

  public function getCommands()
  {
    return $this->commands; 
  }

  /**
   * Cannot be tested because stdin cannot be filled from test
   *
   * @codeCoverageIgnore
   */
  public function runAndListen()
  {
    echo "Welcome to my Console\nQuit with quit()\n\n"; 
    $f = fopen("php://stdin", "r");

    while(true)
    {
      echo ">> "; 
      
      $command = trim(fgets($f)); 

      if (empty($command)) continue; 

      $parts = explode(' ', $command);
      
      echo $this->run($parts); 
    }
  }

  protected function run($parts)
  {
    if (isset($this->commands[$parts[0]]))
    {
      list($command, $runner) = $this->commands[$parts[0]]; 
      $command->clearArguments(); 

      if (count($parts) > 1)
      {
        foreach (array_slice($parts, 1) as $argument)
        {
          $command->addArgument($argument); 
        }
      }

      return $runner->run($command); 
    }
    else
    {
      return "Unknown command\n"; 
    } 
  }
}

