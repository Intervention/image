<?php

use Intervention\Image\Gd\Commands\FitCommand as FitGd;
use Intervention\Image\Imagick\Commands\FitCommand as FitImagick;
use PHPUnit\Framework\TestCase;

class FitCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdFit()
    {
        $cropped_size = Mockery::mock('\Intervention\Image\Size', [800, 400]);
        $cropped_size->shouldReceive('getWidth')->times(2)->andReturn(800);
        $cropped_size->shouldReceive('getHeight')->times(2)->andReturn(400);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', [0, 100]);
        $original_size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'center')->once()->andReturn($cropped_size);
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new FitGd([200, 100]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdFitWithPosition()
    {
        $cropped_size = Mockery::mock('\Intervention\Image\Size', [800, 400]);
        $cropped_size->shouldReceive('getWidth')->times(2)->andReturn(800);
        $cropped_size->shouldReceive('getHeight')->times(2)->andReturn(400);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', [0, 100]);
        $original_size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'top-left')->once()->andReturn($cropped_size);
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new FitGd([200, 100, null, 'top-left']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    /**
     * @requires PHP 5.5
     */
    public function testGdFitWithInterpolation()
    {
        $cropped_size = Mockery::mock('\Intervention\Image\Size', [800, 400]);
        $cropped_size->shouldReceive('getWidth')->times(2)->andReturn(800);
        $cropped_size->shouldReceive('getHeight')->times(2)->andReturn(400);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', [0, 100]);
        $original_size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'center')->once()->andReturn($cropped_size);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $command = Mockery::mock('Intervention\Image\Gd\Commands\FitCommand[modify]', [[200, 100, null, null, IMG_BESSEL]])
            ->shouldAllowMockingProtectedMethods();
        $command->shouldReceive('modify')->with(Mockery::any(), 0, 0, 0, 100, 800, 400, 800, 400, 2)->andReturn(true);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFit()
    {
        $cropped_size = Mockery::mock('\Intervention\Image\Size', [800, 400]);
        $cropped_size->shouldReceive('getWidth')->once()->andReturn(200);
        $cropped_size->shouldReceive('getHeight')->once()->andReturn(100);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', [0, 100]);
        $original_size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'center')->once()->andReturn($cropped_size);
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('cropimage')->with(800, 400, 0, 100)->andReturn(true);
        $imagick->shouldReceive('scaleimage')->with(200, 100)->once()->andReturn(true);
        $imagick->shouldReceive('setimagepage')->with(0, 0, 0, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->times(3)->andReturn($imagick);
        $command = new FitImagick([200, 100]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFitWithPosition()
    {
        $cropped_size = Mockery::mock('\Intervention\Image\Size', [800, 400]);
        $cropped_size->shouldReceive('getWidth')->once()->andReturn(200);
        $cropped_size->shouldReceive('getHeight')->once()->andReturn(100);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', [0, 100]);
        $original_size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'top-left')->once()->andReturn($cropped_size);
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('cropimage')->with(800, 400, 0, 100)->andReturn(true);
        $imagick->shouldReceive('scaleimage')->with(200, 100)->once()->andReturn(true);
        $imagick->shouldReceive('setimagepage')->with(0, 0, 0, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->times(3)->andReturn($imagick);
        $command = new FitImagick([200, 100, null, 'top-left']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
