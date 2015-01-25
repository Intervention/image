<?php

use Intervention\Image\Gd\Commands\FlipCommand as FlipGd;
use Intervention\Image\Imagick\Commands\FlipCommand as FlipImagick;

class FlipCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $command = new FlipGd(array('h'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('flopimage')->with()->andReturn(true);
        $command = new FlipImagick(array('h'));
        $result = $command->execute($image);
        $this->assertTrue($result);

        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('flipimage')->with()->andReturn(true);
        $command = new FlipImagick(array('v'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
