<?php

use Intervention\Image\Gd\Commands\CropCommand as CropGd;
use Intervention\Image\Imagick\Commands\CropCommand as CropImagick;

class CropCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new CropGd(array(100, 150, 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('cropimage')->with(100, 150, 10, 20)->times(3)->andReturn(true);
        $image->getCore()->shouldReceive('setimagepage')->with(0, 0, 0, 0)->times(3);
        
        $command = new CropImagick(array(100, 150, 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
