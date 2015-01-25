<?php

use Intervention\Image\Commands\PolygonCommand;

class PolygonCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $points = array(1, 2, 3, 4, 5, 6);
        $image = $this->getTestImage('gd');
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Gd');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $command = new PolygonCommand(array($points));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

    public function testImagick()
    {
        $points = array(1, 2, 3, 4, 5, 6);
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $driver = Mockery::mock('\Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Imagick');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);

        $command = new PolygonCommand(array($points));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

}
