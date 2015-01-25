<?php

use Intervention\Image\Gd\Commands\InvertCommand as InvertGd;
use Intervention\Image\Imagick\Commands\InvertCommand as InvertImagick;

class InvertCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new InvertGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('negateimage')->with(false)->times(3)->andReturn(true);
        $command = new InvertImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
