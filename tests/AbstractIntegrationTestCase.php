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

    /**
     * Assert if given value is corresponding image core
     *
     * @param  mixed $img 
     * @return void
     */
    abstract protected function assertImageCore($img);

    public function testManager()
    {
        $this->assertInstanceOf('\Intervention\Image\ImageManager', $this->manager());
    }

    public function testMakeFromPath()
    {
        $img = $this->manager()->make('tests/images/animation.gif');
        $this->assertImage($img);
        $this->assertImageSize($img, 20, 15);
        $this->assertImageType($img, 'gif');
        $this->assertImageFile($img, 'tests/images', 'animation.gif', 'animation', 'gif');
        $this->assertImageAnimation($img, 8, array(20, 20, 20, 20, 20, 20, 20, 20));
    }

    public function testMakeFromString()
    {
        $str = file_get_contents('tests/images/animation.gif');
        $img = $this->manager()->make($str);
        $this->assertImage($img);
        $this->assertImageSize($img, 20, 15);
        $this->assertImageType($img, 'gif');
        $this->assertImageFile($img, null, null, null, null);
        $this->assertImageAnimation($img, 8, array(20, 20, 20, 20, 20, 20, 20, 20));
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

    public function testResizeImage()
    {
        $img = $this->manager()->make('tests/images/animation.gif');
        $img->resize(120, 150);
        $this->assertImage($img);
        $this->assertImageSize($img, 120, 150);
        $this->assertImageAnimation($img, 8, array(20, 20, 20, 20, 20, 20, 20, 20));
    }

    public function testResizeImageKeepsTransparency()
    {
        $img = $this->manager()->make('tests/images/circle.png');
        $img->resize(120, 150);
        $this->assertImageSize($img, 120, 150);
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testResizeImageOnlyWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(120, null);
        $this->assertImageSize($img, 120, 16);
    }

    public function testResizeImageOnlyHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(null, 150);
        $this->assertImageSize($img, 16, 150);
    }

    public function testResizeImageAutoHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(50, null, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertImageSize($img, 50, 50);
    }

    public function testResizeImageAutoWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(null, 50, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertImageSize($img, 50, 50);
    }

    public function testResizeDominantWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(100, 120, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertImageSize($img, 100, 100);
    }

    public function testResizeImagePreserveSimpleUpsizing()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(100, 100, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
        $this->assertImageSize($img, 16, 16);
    }

    public function testWidenImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->widen(100);
        $this->assertImageSize($img, 100, 100);
    }

    public function testWidenImageWithConstraint()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->widen(100, function ($constraint) {$constraint->upsize();});
        $this->assertImageSize($img, 16, 16);
    }

    public function testHeightenImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->heighten(100);
        $this->assertImageSize($img, 100, 100);
    }

    public function testHeightenImageWithConstraint()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->heighten(100, function ($constraint) {$constraint->upsize();});
        $this->assertImageSize($img, 16, 16);
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

    private function assertImageAnimation($img, $frames = 2, $delays = null)
    {
        $this->assertEquals($img->getContainer()->countFrames(), $frames);

        foreach ($img as $key => $frame) {
            $this->assertInstanceOf('Intervention\Image\Frame', $frame);
            $this->assertImageCore($frame->getCore());
            if (is_array($delays)) {
                $this->assertEquals($delays[$key], $frame->delay);
            }
        }
    }
}
