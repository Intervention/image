<?php

class AbstractDriverTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testExecuteCommand()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $driver = $this->getMockForAbstractClass('\Intervention\Image\AbstractDriver');
        $command = $driver->executeCommand($image, 'xxxxxxxxxxxxxxxxxxxxxxx', array());
    }
}
