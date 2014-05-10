<?php

class AbstractCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGetArgument()
    {
        $command = $this->getTestCommand();
        $this->assertEquals('foo', $command->getArgument(0));
        $this->assertEquals('bar', $command->getArgument(1));
    }

    public function testGetOutput()
    {
        $command = $this->getTestCommand();
        $command->setOutput('foo');
        $this->assertEquals('foo', $command->getOutput());
    }

    public function testHasOutput()
    {
        $command = $this->getTestCommand();
        $this->assertEquals(false, $command->hasOutput());
        $command->setOutput('foo');
        $this->assertEquals(true, $command->hasOutput());
    }

    public function testSetOutput()
    {
        $command = $this->getTestCommand();
        $command->setOutput('foo');
        $this->assertEquals(true, $command->hasOutput());
    }

    public function getTestCommand()
    {
        $arguments = array('foo', 'bar');
        $command = $this->getMockForAbstractClass('\Intervention\Image\Commands\AbstractCommand', array($arguments));

        return $command;
    }
}
