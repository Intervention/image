<?php

use Intervention\Image\Gd\Commands\HeightenCommand as HeightenGd;
use Intervention\Image\Imagick\Commands\HeightenCommand as HeightenImagick;

class HeightenCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $callback = function ($constraint) { $constraint->aspectRatio(); };
        $image = $this->getTestImage('gd');

        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->once()->andReturn($size);
        $size->shouldReceive('getWidth')->once()->andReturn(800);
        $size->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        
        $command = new HeightenGd(array(200));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $callback = function ($constraint) { $constraint->upsize(); };
        $image = $this->getTestImage('imagick');

        $image->getCore()->shouldReceive('resizeimage')->with(300, 200, \Imagick::FILTER_BOX, 1)->times(3)->andReturn(true);
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->once()->andReturn($size);
        $size->shouldReceive('getWidth')->times(3)->andReturn(300);
        $size->shouldReceive('getHeight')->times(3)->andReturn(200);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        
        $command = new HeightenImagick(array(200));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
