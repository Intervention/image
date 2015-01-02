<?php

use Intervention\Image\Image;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetCore()
    {
        $image = $this->getTestImage();
        $this->assertEquals('core', $image->getCore());
    }

    public function testGetContainer()
    {
        $image = $this->getTestImage();
        $this->assertInstanceOf('\Intervention\Image\ContainerInterface', $image->getContainer());
    }

    public function testSetContainer()
    {
        $image = $this->getTestImage();
        $container = Mockery::mock('\Intervention\Image\ContainerInterface');
        $container->shouldReceive('isReplaced')->andReturn(true);
        $image->setContainer($container);
        $this->assertTrue($image->getContainer()->isReplaced()); 
    }

    public function testCommandCall()
    {
        $image = $this->getTestImage();
        $result = $image->test(1, 2, 3);
        $this->assertEquals('result', $result);   
    }

    public function testEncode()
    {
        $image = $this->getTestImage();
        $image->getDriver()->shouldReceive('encode')->with($image, 'png', 90)->once();
        $image->encode('png', 90);
    }

    public function testSave()
    {
        $save_as = __DIR__.'/tmp/test.jpg';
        $image = $this->getTestImage();
        $image->getDriver()->shouldReceive('encode')->with($image, 'jpg', 85)->once()->andReturn('mock');
        $image = $image->save($save_as, 85);
        $this->assertInstanceOf('\Intervention\Image\Image', $image);
        $this->assertFileExists($save_as);
        $this->assertEquals($image->basename, 'test.jpg');
        $this->assertEquals($image->extension, 'jpg');
        $this->assertEquals($image->filename, 'test');
        @unlink($save_as);
    }

    public function testFilter()
    {
        $demoFilter = Mockery::mock('\Intervention\Image\Filters\DemoFilter', array(15));
        $image = $this->getTestImage();
        $demoFilter->shouldReceive('applyFilter')->with($image)->once()->andReturn($image);
        $image->filter($demoFilter);
    }

    public function testMime()
    {
        $image = $this->getTestImage();
        $this->assertEquals('image/png', $image->mime());
    }

    public function testBasePath()
    {
        $image = $this->getTestImage();
        $this->assertEquals('./tmp/foo.png', $image->basePath());
    }

    private function getTestImage()
    {
        $size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $driver = Mockery::mock('\Intervention\Image\AbstractDriver');
        $container = Mockery::mock('\Intervention\Image\ContainerInterface');
        $container->shouldReceive('setCore');
        $container->shouldReceive('getCore')->andReturn('core');
        $command = Mockery::mock('\Intervention\Image\Commands\AbstractCommand');
        $command->shouldReceive('hasOutput')->andReturn(true);
        $command->shouldReceive('getOutput')->andReturn('result');
        $driver->shouldReceive('executeCommand')->andReturn($command);
        $image = new Image($driver, $container);
        $image->mime = 'image/png';
        $image->dirname = './tmp';
        $image->basename = 'foo.png';

        return $image;
    }
}
