<?php

use Intervention\Image\Gd\Commands\BlurCommand as BlurGd;
use Intervention\Image\Imagick\Commands\BlurCommand as BlurImagick;

class BlurCommandTest extends CommandTestCase
{
    public function testGd()
    {
        $image = $this->getTestImage('gd');

        $command = new BlurGd(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('blurimage')->with(2, 1)->times(3)->andReturn(true);

        $command = new BlurImagick(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
