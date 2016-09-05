<?php

abstract class AbstractIntegrationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Returns ImageManager instance
     *
     * @return \Intervention\Image\ImageManager
     */
    abstract protected function manager();

    /**
     * Returns Core
     *
     * @return resource|Imagick
     */
    abstract protected function core();

    /**
     * Assert if given value is instance of Intervention Image
     *
     * @param  mixed $img 
     * @return void
     */
    abstract protected function assertImage($img);

    public function testManager()
    {
        $this->assertInstanceOf('\Intervention\Image\ImageManager', $this->manager());
    }

    public function testMakeFromPath()
    {
        $img = $this->manager()->make('tests/images/circle.png');
        $this->assertImage($img);
        $this->assertImageSize($img, 50, 50);
        $this->assertImageType($img, 'png');
        $this->assertImageFile($img, 'tests/images', 'circle.png', 'circle', 'png');
    }

    public function testMakeFromString()
    {
        $str = file_get_contents('tests/images/circle.png');
        $img = $this->manager()->make($str);
        $this->assertImage($img);
        $this->assertImageSize($img, 50, 50);
        $this->assertImageType($img, 'png');
        $this->assertImageFile($img, null, null, null, null);
    }

    public function testMakeFromCore()
    {
        $img = $this->manager()->make($this->core());
        $this->assertImage($img);
        $this->assertImageSize($img, 50, 50);
        $this->assertImageFile($img, null, null, null, null);   
    }

    public function testMakeFromDataUrl()
    {
        $img = $this->manager()->make('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertImage($img);
        $this->assertImageSize($img, 10, 10);
        $this->assertImageFile($img, null, null, null, null);   
    }

    public function testMakeFromBase64()
    {
        $img = $this->manager()->make('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertImage($img);
        $this->assertImageSize($img, 10, 10);
        $this->assertImageFile($img, null, null, null, null);   
    }

    public function testCanvas()
    {
        $img = $this->manager()->canvas(30, 20);
        $this->assertImage($img);
        $this->assertImageSize($img, 30, 20);
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testCanvasWithSolidBackground()
    {
        $img = $this->manager()->canvas(30, 20, 'b53717');
        $this->assertImage($img);
        $this->assertImageSize($img, 30, 20);
        $this->assertColorAt($img, 15, 15, '#b53717');
    }

    public function testGetSize()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $size = $img->getSize();
        $this->assertInstanceOf('Intervention\Image\Size', $size);
        $this->assertInternalType('int', $size->width);
        $this->assertInternalType('int', $size->height);
        $this->assertEquals(16, $size->width);
        $this->assertEquals(16, $size->height);
    }

    private function assertImageSize($img, $width, $height)
    {
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($width, $img->getWidth());
        $this->assertEquals($height, $img->getHeight());
    }

    private function assertImageType($img, $type)
    {
        switch (strtolower($type)) {
            case 'png':
                $mime = 'image/png';
                break;

            case 'jpg':
                $mime = 'image/jpeg';
                break;

            case 'gif':
                $mime = 'image/gif';
                break;

            default:
                $mime = $type;
                break;
        }

        $this->assertEquals($mime, $img->mime);
    }

    private function assertImageFile($img, $dirname, $basename, $filename, $extension)
    {
        $this->assertEquals($dirname, $img->dirname);
        $this->assertEquals($basename, $img->basename);
        $this->assertEquals($filename, $img->filename);
        $this->assertEquals($extension, $img->extension);
    }

    private function assertTransparentPosition($img, $x, $y, $transparent = 0)
    {
        // background should be transparent
        $color = $img->pickColor($x, $y, 'array');
        $this->assertEquals($transparent, $color[3]); // alpha channel
    }

    private function assertColorAt($img, $x, $y, $color)
    {
        $this->assertEquals($color, $img->pickColor(15, 15, 'hex'));
    }
}
