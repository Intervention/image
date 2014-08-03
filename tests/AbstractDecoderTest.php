<?php

use \Intervention\Image\AbstractDecoder;

class AbstractDecoderTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testIsImagick()
    {
        $source = $this->getTestDecoder(new \Imagick);
        $this->assertTrue($source->isImagick());

        $source = $this->getTestDecoder(new StdClass);
        $this->assertFalse($source->isImagick());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isImagick());
    }

    public function testIsGdResource()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $source = $this->getTestDecoder($resource);
        $this->assertTrue($source->isGdResource());

        $source = $this->getTestDecoder(tmpfile());
        $this->assertFalse($source->isGdResource());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isGdResource());
    }

    public function testIsFilepath()
    {
        $source = $this->getTestDecoder(__DIR__.'/AbstractDecoderTest.php');
        $this->assertTrue($source->isFilepath());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isFilepath());

        $source = $this->getTestDecoder(array());
        $this->assertFalse($source->isFilepath());

        $source = $this->getTestDecoder(new StdClass);
        $this->assertFalse($source->isFilepath());
    }

    public function testIsUrl()
    {
        $source = $this->getTestDecoder('http://foo.bar');
        $this->assertTrue($source->isUrl());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isUrl());
    }

    public function testIsBinary()
    {
        $source = $this->getTestDecoder(file_get_contents(__DIR__.'/images/test.jpg'));
        $this->assertTrue($source->isBinary());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestDecoder(1);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestDecoder(0);
        $this->assertFalse($source->isBinary());

        $source = $this->getTestDecoder(array(1,2,3));
        $this->assertFalse($source->isBinary());

        $source = $this->getTestDecoder(new StdClass);
        $this->assertFalse($source->isBinary());
    }

    public function testIsInterventionImage()
    {
        $source = $this->getTestDecoder(1);
        $this->assertFalse($source->isInterventionImage());

        $img = Mockery::mock('Intervention\Image\Image');
        $source = $this->getTestDecoder($img);
        $this->assertTrue($source->isInterventionImage());
    }

    public function testIsSymfonyUpload()
    {
        $source = $this->getTestDecoder(1);
        $this->assertFalse($source->isSymfonyUpload());

        $img = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
        $source = $this->getTestDecoder($img);
        $this->assertTrue($source->isSymfonyUpload());
    }

    public function getTestDecoder($data)
    {
        return $this->getMockForAbstractClass('\Intervention\Image\AbstractDecoder', array($data));
    }
}
