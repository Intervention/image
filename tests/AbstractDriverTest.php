<?php

use PHPUnit\Framework\TestCase;

class AbstractDriverTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     */
    public function testExecuteCommand()
    {
        $this->setExpectedException(\Intervention\Image\Exception\NotSupportedException::class);

        $image = Mockery::mock('Intervention\Image\Image');
        $driver = $this->getMockForAbstractClass('\Intervention\Image\AbstractDriver');
        $command = $driver->executeCommand($image, 'xxxxxxxxxxxxxxxxxxxxxxx', []);
    }
}
