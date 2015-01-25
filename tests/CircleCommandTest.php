<?php

use Intervention\Image\Commands\CircleCommand;

class CircleCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Gd');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);

        $command = new CircleCommand(array(250, 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');

        $image->getCore()->shouldReceive('drawimage')->times(3);
        $driver = Mockery::mock('\Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Imagick');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);

        $command = new CircleCommand(array(25, 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

}
