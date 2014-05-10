<?php

use \Intervention\Image\AbstractSource;

class AbstractSourceTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testIsImagick()
    {
        $source = $this->getTestSource(new \Imagick);
        $this->assertTrue($source->isImagick());

        $source = $this->getTestSource(new StdClass);
        $this->assertFalse($source->isImagick());

        $source = $this->getTestSource(null);
        $this->assertFalse($source->isImagick());
    }

    public function testIsGdResource()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $source = $this->getTestSource($resource);
        $this->assertTrue($source->isGdResource());

        $source = $this->getTestSource(tmpfile());
        $this->assertFalse($source->isGdResource());

        $source = $this->getTestSource(null);
        $this->assertFalse($source->isGdResource());
    }

    public function testIsFilepath()
    {
        $source = $this->getTestSource(__DIR__.'/AbstractSourceTest.php');
        $this->assertTrue($source->isFilepath());

        $source = $this->getTestSource(null);
        $this->assertFalse($source->isFilepath());

        $source = $this->getTestSource(array());
        $this->assertFalse($source->isFilepath());

        $source = $this->getTestSource(new StdClass);
        $this->assertFalse($source->isFilepath());
    }

    public function testIsUrl()
    {
        $source = $this->getTestSource('http://foo.bar');
        $this->assertTrue($source->isUrl());

        $source = $this->getTestSource(null);
        $this->assertFalse($source->isUrl());
    }

    public function testIsBinary()
    {
        $source = $this->getTestSource(file_get_contents(__DIR__.'/images/test.jpg'));
        $this->assertTrue($source->isBinary());

        $source = $this->getTestSource(null);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestSource(1);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestSource(0);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestSource(array(1,2,3));
        $this->assertFalse($source->isBinary());

        $source = $this->getTestSource(new StdClass);
        $this->assertFalse($source->isBinary());
    }

    public function getTestSource($data)
    {
        return $this->getMockForAbstractClass('\Intervention\Image\AbstractSource', array($data));
    }
}
