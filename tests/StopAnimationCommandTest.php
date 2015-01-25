<?php

use Intervention\Image\Gd\Commands\StopAnimationCommand as StopAnimationGd;
use Intervention\Image\Imagick\Commands\StopAnimationCommand as StopAnimationImagick;

class StopAnimationCommandTest extends CommandTestCase
{
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $image->shouldReceive('setContainer')->once();

        $command = new StopAnimationGd(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $newContainer = Mockery::mock('Intervention\Image\Imagick\Container');
        $frame = Mockery::mock('Intervention\Image\Image');
        $frame->shouldReceive('getContainer')->once()->andReturn($newContainer);
        $driver = Mockery::mock('Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('init')->with('blob')->once()->andReturn($frame);

        $image = $this->getTestImage('imagick');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->getCore()->shouldReceive('getimageblob')->once()->andReturn('blob');
        $image->getCore()->shouldReceive('clear')->once();
        $image->shouldReceive('setContainer')->with($newContainer)->once();

        $command = new StopAnimationImagick(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
