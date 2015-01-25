<?php

abstract class CommandTestCase extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    protected function getTestImage($type)
    {
        switch (strtolower($type)) {
            case 'gd':
                $image = Mockery::mock('Intervention\Image\Image');
                $container = Mockery::mock('Intervention\Image\Gd\Container');
                $frame = Mockery::mock('Intervention\Image\Frame');

                $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
                $iterator = new ArrayIterator(array($frame));

                $container->shouldReceive('getIterator')->andReturn($iterator);
                $frame->shouldReceive('getCore')->andReturn($resource);
                $frame->shouldReceive('setCore')->andReturn($resource);
                $image->shouldReceive('getCore')->andReturn($resource);
                $image->shouldReceive('getIterator')->andReturn($container);
                break;

            case 'imagick':
                $image = Mockery::mock('Intervention\Image\Image');
                $container = Mockery::mock('Intervention\Image\Imagick\Container');
                $imagick = Mockery::mock('Imagick');
                $frame = Mockery::mock('Intervention\Image\Frame');

                $iterator = new ArrayIterator(array($imagick));

                $frame->shouldReceive('getCore')->andReturn($imagick);
                $container->shouldReceive('rewind')->once();
                $container->shouldReceive('valid')->once()->andReturn(true);
                $container->shouldReceive('valid')->once()->andReturn(true);
                $container->shouldReceive('valid')->once()->andReturn(true);
                $container->shouldReceive('valid')->once()->andReturn(false);
                $container->shouldReceive('current')->times(3)->andReturn($frame);
                $container->shouldReceive('next');
                $container->shouldReceive('key');
                $image->shouldReceive('getIterator')->once()->andReturn($container);
                $image->shouldReceive('getCore')->andReturn($imagick);
                $imagick->shouldReceive('rewind');
                $imagick->shouldReceive('current')->andReturn($imagick);
                $imagick->shouldReceive('valid')->andReturn(true);
                break;

            default:
                throw new Exception('No type defined');
        }

        return $image;
    }
}
