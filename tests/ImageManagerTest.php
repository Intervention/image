<?php

namespace Intervention\Image\Tests;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageManagerTest extends TestCase
{
    public function testConstructor()
    {
        $manager = new ImageManager(['foo' => 'bar']);
        $this->assertInstanceOf(ImageManager::class, $manager);
        $this->assertEquals('gd', $manager->getConfig('driver'));
        $this->assertEquals('bar', $manager->getConfig('foo'));
    }

    public function testConfigure()
    {
        $manager = new ImageManager(['foo' => 'bar']);
        $manager->configure(['foo' => 'baz', 'driver' => 'foo']);
        $this->assertEquals('foo', $manager->getConfig('driver'));
        $this->assertEquals('baz', $manager->getConfig('foo'));
    }

    public function testGetConfig()
    {
        $manager = new ImageManager(['foo' => 'bar']);
        $this->assertEquals('gd', $manager->getConfig('driver'));
        $this->assertEquals('bar', $manager->getConfig('foo'));
    }

    public function testCreateGd()
    {
        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testMakeGd()
    {
        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->make(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
