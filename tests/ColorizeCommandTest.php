<?php

use Intervention\Image\Gd\Commands\ColorizeCommand as ColorizeGd;
use Intervention\Image\Imagick\Commands\ColorizeCommand as ColorizeImagick;

class ColorizeCommandTest extends CommandTestCase
{
    public function testGd()
    {
        $image = $this->getTestImage('gd');

        $command = new ColorizeGd(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('getquantumrange')->times(3)->with()->andReturn(array('quantumRangeLong' => 42));
        $image->getCore()->shouldReceive('levelimage')->times(3)->with(0, 4, 42, \Imagick::CHANNEL_RED)->andReturn(true);
        $image->getCore()->shouldReceive('levelimage')->times(3)->with(0, 1, 42, \Imagick::CHANNEL_GREEN)->andReturn(true);
        $image->getCore()->shouldReceive('levelimage')->times(3)->with(0, 0.6, 42, \Imagick::CHANNEL_BLUE)->andReturn(true);
        
        $command = new ColorizeImagick(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
