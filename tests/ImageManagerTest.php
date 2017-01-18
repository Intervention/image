<?php

use Intervention\Image\ImageManager;

class ImageManagerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $config = array('driver' => 'foo', 'bar' => 'baz');
        $manager = new ImageManager($config);
        $this->assertEquals('foo', $manager->config['driver']);
        $this->assertEquals('baz', $manager->config['bar']);
    }

    public function testConfigure()
    {
        $overwrite = array('driver' => 'none', 'bar' => 'none');
        $config = array('driver' => 'foo', 'bar' => 'baz');
        $manager = new ImageManager($overwrite);
        $manager->configure($config);
        $this->assertEquals('foo', $manager->config['driver']);
        $this->assertEquals('baz', $manager->config['bar']);
    }

    public function testConfigureObject()
    {
        $config = array('driver' => new Intervention\Image\Imagick\Driver());
        $manager = new ImageManager($config);

        $image = $manager->make('data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $this->assertInstanceOf('Intervention\Image\Image', $image);
        $this->assertInstanceOf('Imagick', $image->getCore());
    }
}
