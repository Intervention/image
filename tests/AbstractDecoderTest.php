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

    public function testIsSplFileInfo()
    {
        $source = $this->getTestDecoder(1);
        $this->assertFalse($source->isSplFileInfo());

        $img = Mockery::mock('SplFileInfo');
        $source = $this->getTestDecoder($img);
        $this->assertTrue($source->isSplFileInfo());

        $img = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile', 'SplFileInfo');
        $this->assertTrue($source->isSplFileInfo());
    }

    public function testIsSymfonyUpload()
    {
        $source = $this->getTestDecoder(1);
        $this->assertFalse($source->isSymfonyUpload());

        $img = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
        $source = $this->getTestDecoder($img);
        $this->assertTrue($source->isSymfonyUpload());
    }

    public function testIsDataUrl()
    {
        $source = $this->getTestDecoder('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertTrue($source->isDataUrl());

        $source = $this->getTestDecoder(null);
        $this->assertFalse($source->isDataUrl());
    }

    public function testIsBase64()
    {
        $decoder = $this->getTestDecoder(null);
        $this->assertFalse($decoder->isBase64());

        $decoder = $this->getTestDecoder('random');
        $this->assertFalse($decoder->isBase64());

        $base64 = "iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC";
        $decoder = $this->getTestDecoder($base64);
        $this->assertTrue($decoder->isBase64());
    }

    public function getTestDecoder($data)
    {
        return $this->getMockForAbstractClass('\Intervention\Image\AbstractDecoder', array($data));
    }
}
