<?php

use Intervention\Image\Gd\Commands\ResizeCommand as ResizeCommandGd;
use Intervention\Image\Imagick\Commands\ResizeCommand as ResizeCommandImagick;

class resizeCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $callback = function ($constraint) { $constraint->upsize(); };
        $image = $this->getTestImage('gd');
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->with(300, 200, $callback)->once()->andReturn($size);
        $size->shouldReceive('getWidth')->once()->andReturn(800);
        $size->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getSize')->once()->andReturn($size);

        $command = new ResizeCommandGd(array(300, 200, $callback));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $callback = function ($constraint) { $constraint->upsize(); };
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('resizeimage')->with(300, 200, \Imagick::FILTER_BOX, 1)->times(3)->andReturn(true);
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->with(300, 200, $callback)->once()->andReturn($size);
        $size->shouldReceive('getWidth')->times(3)->andReturn(300);
        $size->shouldReceive('getHeight')->times(3)->andReturn(200);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        
        $command = new ResizeCommandImagick(array(300, 200, $callback));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
