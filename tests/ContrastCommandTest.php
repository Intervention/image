<?php

use Intervention\Image\Gd\Commands\ContrastCommand as ContrastGd;
use Intervention\Image\Imagick\Commands\ContrastCommand as ContrastImagick;

class ContrastCommandTest extends CommandTestCase
{
    public function testGd()
    {
        $image = $this->getTestImage('gd');

        $command = new ContrastGd(array(20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('sigmoidalcontrastimage')->times(3)->with(true, 5, 0)->andReturn(true);

        $command = new ContrastImagick(array(20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
