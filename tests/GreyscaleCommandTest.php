<?php

use Intervention\Image\Gd\Commands\GreyscaleCommand as GreyscaleGd;
use Intervention\Image\Imagick\Commands\GreyscaleCommand as GreyscaleImagick;

class GreyscaleCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new GreyscaleGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('modulateimage')->times(3)->with(100, 0, 100)->andReturn(true);
        $command = new GreyscaleImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
