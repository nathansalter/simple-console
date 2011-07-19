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
     $this->assertEquals($numberOfCommands + 1, count($console->getCommands()), '->registerCommand() registers command'); 
  }

  public function testRegisterCommand()
  {
    $console = new Console(); 
    $console->registerCommand(new Command\Command('foo', 'bar'));
    $commands = $console->getCommands(); 

    $this->assertArrayHasKey('bar', $commands, '->registerCommand() alias is registered'); 
    $this->assertEquals('foo', $commands['bar'][0]->getCommand(), '->registerCommand() command name is registered');  
  }

  /**
   *  @dataProvider runDataProvider
   */
  public function testRun($parts)
  {
    require_once(__DIR__ . '/Runner/TestRunner.php'); 

    $console = new Console(); 
    $console->registerCommand(new Command\Command('foo', 'bar'), new \Console\Tests\Console\Runner\TestRunner()); 
    
    $reflectionMethod = new \ReflectionMethod('\\Console\\Console', 'run');
    $reflectionMethod->setAccessible(true); 

    $this->assertEquals(join(' ', $parts), $reflectionMethod->invoke($console, $parts));  
  }

  public function runDataProvider()
  {
    return array(
      array(array('bar', '-l', '~')), 
      array(array('bar')), 
    ); 
  }
}

