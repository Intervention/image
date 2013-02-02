<?php

use Intervention\Image\Image;
use Intervention\Image\Font;

class ImageTest extends PHPUnit_Framework_Testcase
{
    private function getTestImage()
    {
        return new Image('public/test.jpg');
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
        $this->assertEquals($img->basename, 'test.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'test');
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
        $img->insert('public/test.jpg', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        
    }

    public function testPixelateImage()
    {
        $img = $this->getTestImage();
        $img->pixelate(20);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testGreyscaleImage()
    {
        $img = $this->getTestImage();
        $img->greyscale();
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testFillImage()
    {
        $img = $this->getTestImage();
        $img = $img->fill('fdf5e4');
        $this->assertInstanceOf('Intervention\Image\Image', $img);

        $img = $img->fill(array(155, 155, 155), rand(1,10), rand(1,10));
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testPixelImage()
    {
        $img = $this->getTestImage();
        $img = $img->pixel('fdf5e4', rand(1,10), rand(1,10));
        $this->assertInstanceOf('Intervention\Image\Image', $img);

        $img = $img->pixel(array(255, 255, 255), rand(1,10), rand(1,10));
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testTextImage()
    {
        $img = $this->getTestImage();
        $img = $img->text('Fox', 10, 10, 16, '000000', 0, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testRectangleImage()
    {
        $img = $this->getTestImage();
        $img = $img->rectangle('cccccc', 10, 10, 100, 100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testLineImage()
    {
        $img = $this->getTestImage();
        $img = $img->line('cccccc', 10, 10, 100, 100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testEllipseImage()
    {
        $img = $this->getTestImage();
        $img = $img->ellipse('cccccc', 10, 10, 100, 50, false);
        $img = $img->ellipse('666666', 100, 100, 50, 100, true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testCircleImage()
    {
        $img = $this->getTestImage();
        $img = $img->circle('cccccc', 10, 10, 100, false);
        $img = $img->circle('666666', 100, 100, 50, true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testAddImagesWithAlphaChannel()
    {
        $img = $this->getTestImage();
        $circle = new Image('public/circle.png');

        for ($x=0; $x < $img->width; $x=$x+$circle->width) { 
            for ($y=0; $y < $img->height; $y=$y+$circle->height) { 
                // insert circle png at position x,y
                $img->insert($circle, $x, $y);
            }
        }

        $save_as = 'public/final.png';
        $img->save($save_as);
        $this->assertFileExists($save_as);
        @unlink($save_as);

        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInstanceOf('Intervention\Image\Image', $circle);
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