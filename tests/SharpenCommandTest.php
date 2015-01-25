<?php

use Intervention\Image\Gd\Commands\SharpenCommand as SharpenGd;
use Intervention\Image\Imagick\Commands\SharpenCommand as SharpenImagick;

class SharpenCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new SharpenGd(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('unsharpmaskimage')->with(1, 1, 8, 0)->andReturn(true);
        $command = new SharpenImagick(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
