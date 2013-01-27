<?php

use Intervention\Image\Image;

class ImageTest extends PHPUnit_Framework_Testcase
{
    private function getTestImage()
    {
        return new Image('public/gun.jpg');
    }

    public function testFilesystemLibraryIsAvailable()
    {
        $img = new Image;
        $this->assertInstanceOf('Illuminate\Filesystem\Filesystem', $img->getFilesystem());
    }

    public function testCreationFromFile()
    {
        $img = $this->getTestImage();
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
        $this->assertEquals($img->dirname, 'public');
        $this->assertEquals($img->basename, 'gun.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'gun');
    }

    public function testResizeImage()
    {
        $img = $this->getTestImage();
        $img->resize(320, 240);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);

        // auto height
        $img = $this->getTestImage();
        $img->resize(array('width' => '320'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);

        // auto width
        $img = $this->getTestImage();
        $img->resize(array('height' => '240'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);
    }

    public function testGrabImage()
    {
        $img = $this->getTestImage();
        $img->grab(200);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 200);
        $this->assertEquals($img->height, 200);

        $img = $this->getTestImage();
        $img->grab(200, 100);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 200);
        $this->assertEquals($img->height, 100);

        $img = $this->getTestImage();
        $img->grab(array('width' => '320', 'height' => '100'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 100);

        $img = $this->getTestImage();
        $img->grab(array('width' => '100'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);

        $img = $this->getTestImage();
        $img->grab(array('height' => '100'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);
    }

    public function testInsertImage()
    {
        $img = $this->getTestImage();
        $img->insert('public/gun.jpg', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        
    }

    public function testPixelateImage()
    {
        $img = $this->getTestImage();
        $img->pixelate(20);
        $img->save('public/pixels.jpg');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testResetImage()
    {
        $img = $this->getTestImage();
        $img->resize(300, 200);
        $img->reset();
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    public function testSaveImage()
    {
        $save_as = 'public/test2.jpg';
        $img = $this->getTestImage();
        $img->save($save_as);
        $this->assertFileExists($save_as);
        @unlink($save_as);
    }

    public function testStringConversion()
    {
        $img = $this->getTestImage();
        $img = strval($img);
        $this->assertInternalType('string', $img);
    }

}