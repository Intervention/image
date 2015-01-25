<?php

use Intervention\Image\Gd\Commands\RotateCommand as RotateGd;
use Intervention\Image\Imagick\Commands\RotateCommand as RotateImagick;

class RotateCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new RotateGd(array(45, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $pixel = Mockery::mock('ImagickPixel', array('#b53717'));
        $image->getCore()->shouldReceive('rotateimage')->andReturn(true);
        $command = new RotateImagick(array(45, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
