<?php

use Intervention\Image\ImageManagerStatic;

class ImageManagerStaticTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testNewInstance()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $managerStatic = new ImageManagerStatic($manager);
        $m = $managerStatic->newInstance();
        $this->assertInstanceOf('Intervention\Image\ImageManagerStatic', $m);
    }

    public function testMake()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $managerStatic = new ImageManagerStatic($manager);
        $img = $managerStatic->make('tests/images/tile.png');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testCanvas()
    {
        $manager = Mockery::mock('Intervention\Image\ImageManager');
        $managerStatic = new ImageManagerStatic($manager);
        $img = $managerStatic->canvas(100, 100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }
}
