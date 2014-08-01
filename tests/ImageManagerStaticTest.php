<?php

use Intervention\Image\ImageManagerStatic;

class ImageManagerStaticTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetManager()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $managerStatic = new ImageManagerStatic($manager);
        $m = $managerStatic->getManager();
        $this->assertInstanceOf('Intervention\Image\ImageManager', $m);
    }

    public function testMake()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $manager->shouldReceive('make')->with('foo')->once();
        $managerStatic = new ImageManagerStatic($manager);
        $managerStatic->make('foo');
    }

    public function testCanvas()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $manager->shouldReceive('canvas')->with(100, 100, null)->once();
        $managerStatic = new ImageManagerStatic($manager);
        $managerStatic->canvas(100, 100);
    }
}
