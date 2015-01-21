<?php

use Intervention\Image\Imagick\Container;

class ImagickContainerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSetGetCore()
    {
        $imagick = Mockery::mock('Imagick');
        $container = new Container($imagick);
        $container->setCore('foo');
        $this->assertEquals('foo', $container->getCore());
    }

    public function testCountFrames()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('getnumberimages')->once()->andReturn(3);
        $container = new Container($imagick);
        $this->assertEquals(3, $container->countFrames());
    }
}
