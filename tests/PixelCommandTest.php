<?php

use Intervention\Image\Gd\Commands\PixelCommand as PixelGd;
use Intervention\Image\Imagick\Commands\PixelCommand as PixelImagick;

class PixelCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new PixelGd(array('#b53717', 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('drawimage')->times(3)->andReturn(true);
        $command = new PixelImagick(array('#b53717', 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
