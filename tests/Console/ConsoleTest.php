<?php

namespace Console\Tests\Console; 

use Console\Console; 
use Console\Command; 

class ConsoleTestCase extends \PHPUnit_Framework_TestCase
{
  public function testNumberOfRegisteredCommands()
  {
     $console = new Console(); 
     $numberOfCommands = count($console->getCommands()); 

     $console->registerCommand(new Command\Command('foo', 'bar')); 
     $this->assertEquals($numberOfCommands + 1, count($console->getCommands()), '->registerCommand() registers the command into console'); 
  }

  public function testRegisterCommand()
  {
    $console = new Console(); 
    $console->registerCommand(new Command\Command('foo', 'bar'));
    $commands = $console->getCommands(); 

    $this->assertArrayHasKey('bar', $commands, '->registerCommand() alias is registered'); 
    $this->assertEquals('foo', $commands['bar'][0]->getCommand(), '->registerCommand() registers the command into console');  
  }

  /**
   *  @dataProvider parseDataProvider
   */
  public function testParse($parts)
  {
    require_once(__DIR__ . '/Runner/TestRunner.php'); 

    $console = new Console(); 
    $console->registerCommand(new Command\Command('foo', 'bar'), new \Console\Tests\Console\Runner\TestRunner()); 
    
    $reflectionMethod = new \ReflectionMethod('\\Console\\Console', 'parseAndRun');
    $reflectionMethod->setAccessible(true); 

    $this->assertEquals(join(' ', $parts), $reflectionMethod->invoke($console, $parts), '->parseAndRun() parses the command input');  
  }

  public function parseDataProvider()
  {
    return array(
      array(array('bar', '-l', '~')), 
      array(array('bar')), 
    ); 
  }

  public function testRunAndListen()
  {
    require_once(__DIR__ . '/Runner/TestExitRunner.php'); 

    // the handler is closed by runAndListen()
    $handler = tmpfile(); 
    fwrite($handler, "bar -l ~\n");
    fwrite($handler, "quit()\n");  
    fseek($handler, 0); 

    $console = new Console(null, $handler); 
    $console->registerCommand(new Command\Command('foo', 'bar'), new \Console\Tests\Console\Runner\TestRunner()); 
    $console->registerCommand(new Command\Command('exit', 'quit()'), new \Console\Tests\Console\Runner\TestExitRunner()); 

    $reflectionMethod = new \ReflectionMethod('\\Console\\Console', 'runAndListen');
    $reflectionMethod->setAccessible(true); 

    ob_start();
    ob_implicit_flush(0);
    $reflectionMethod->invoke($console); 
    $result = ob_get_clean(); 

    $this->assertEquals(">> bar -l ~>> ", $result, 'runAndListen() runs the commands'); 
  }
}

