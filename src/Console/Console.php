<?php

namespace Console; 

class Console
{
  protected
    $defaultRunner, 
    $commands = array(), 
    $inputHandler; 

  public function __construct(Runner\AbstractRunner $runner = null, $inputHandler = null)
  {
    $this->runner = null === $runner ? new Runner\NullRunner() : $runner; 
    $this->inputHandler = $inputHandler; 

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
   * @codeCoverageIgnore
   */
  public function run()
  {
    echo "Welcome to my Console\nQuit with quit()\n\n"; 
    $this->runAndListen(); 
  }

  protected function runAndListen()
  {
    $fp = $this->inputHandler !== null ? $this->inputHandler : fopen("php://stdin", "r"); 

    while(true)
    {
      echo ">> "; 
      
      $command = trim(fgets($fp)); 

      if (empty($command)) continue; 

      $parts = explode(' ', $command);
      
      $result = $this->parseAndRun($parts); 

      if (false === $result)
      {
        break; 
      }
      else
      {
        echo $result; 
      }
    }

    fclose($fp); 
  }

  protected function parseAndRun($parts)
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

