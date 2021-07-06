<?php

use PHPUnit\Framework\TestCase;

class AbstractDriverTest extends TestCase
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
        $command = $driver->executeCommand($image, 'xxxxxxxxxxxxxxxxxxxxxxx', []);
    }
}
