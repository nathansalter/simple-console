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
      
      $this->run($parts); 
    }
  }

  protected function run($parts)
  {
    if (isset($this->commands[$parts[0]]))
    {
      list($command, $runner) = $this->commands[$parts[0]]; 

      if (count($parts) > 1)
      {
        foreach (array_slice($parts, 1) as $argument)
        {
          $command->addArgument($argument); 
        }
      }

      echo $runner->run($command); 
    }
    else
    {
      echo "Unknown command\n"; 
    } 
  }
}

namespace Console\Runner; 

abstract class AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
  }
}

class NullRunner extends AbstractRunner
{
}

class Shell extends AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    $output = array(); 
    exec($command->getCommand() . ' ' . join(' ', $command->getArguments()), $output);

    return join("\n", $output) . "\n"; 
  }
}

class PHP extends AbstractRunner
{
  public function run(\Console\Command\AbstractCommand $command)
  {
    eval($command->getCommand()); 
  }
}

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
}


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
