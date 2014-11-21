<?php

class GdSystemTest extends PHPUnit_Framework_TestCase
{
    public function testMakeFromPath()
    {
        $img = $this->manager()->make('tests/images/circle.png');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertEquals('image/png', $img->mime);
        $this->assertEquals('tests/images', $img->dirname);
        $this->assertEquals('circle.png', $img->basename);
        $this->assertEquals('png', $img->extension);
        $this->assertEquals('circle', $img->filename);
        $this->assertEquals('image/png', $img->mime);
    }

    public function testMakeFromString()
    {
        $str = file_get_contents('tests/images/circle.png');
        $img = $this->manager()->make($str);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertEquals('image/png', $img->mime);
    }

    public function testMakeFromResource()
    {
        $resource = imagecreatefrompng('tests/images/circle.png');
        $img = $this->manager()->make($resource);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
    }

    public function testMakeFromDataUrl()
    {
        $img = $this->manager()->make('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
    }

    public function testMakeFromBase64()
    {
        $img = $this->manager()->make('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
    }

    public function testCanvas()
    {
        $img = $this->manager()->canvas(30, 20);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(30, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testCanvasWithSolidBackground()
    {
        $img = $this->manager()->canvas(30, 20, 'b53717');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(30, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertEquals('#b53717', $img->pickColor(15, 15, 'hex'));
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
        $img = $this->manager()->make('tests/images/circle.png');
        $img->resize(120, 150);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(120, $img->getWidth());
        $this->assertEquals(150, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testResizeImageOnlyWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(120, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(120, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 15);
    }

    public function testResizeImageOnlyHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(null, 150);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(150, $img->getHeight());
        $this->assertTransparentPosition($img, 15, 0);
    }

    public function testResizeImageAutoHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(50, null, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertTransparentPosition($img, 30, 0);
    }

    public function testResizeImageAutoWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(null, 50, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertTransparentPosition($img, 30, 0);
    }

    public function testResizeDominantWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(100, 120, function ($constraint) { $constraint->aspectRatio(); });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testResizeImagePreserveSimpleUpsizing()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resize(100, 100, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 15, 0);
    }

    public function testWidenImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->widen(100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testWidenImageWithConstraint()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->widen(100, function ($constraint) {$constraint->upsize();});
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 8, 0);
    }

    public function testHeightenImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->heighten(100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testHeightenImageWithConstraint()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->heighten(100, function ($constraint) {$constraint->upsize();});
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->getCore());
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 8, 0);
    }

    public function testResizeCanvasCenter()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 4);
        $this->assertColorAtPosition('#445160', $img, 5, 5);
        $this->assertTransparentPosition($img, 0, 5);
        $this->assertTransparentPosition($img, 5, 4);
    }

    public function testResizeCanvasTopLeft()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'top-left');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertTransparentPosition($img, 0, 8);
        $this->assertTransparentPosition($img, 8, 7);
    }

    public function testResizeCanvasTop()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'top');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#445160', $img, 5, 8);
        $this->assertTransparentPosition($img, 0, 8);
        $this->assertTransparentPosition($img, 5, 7);
    }

    public function testResizeCanvasTopRight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'top-right');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#445160', $img, 2, 8);
        $this->assertTransparentPosition($img, 0, 8);
        $this->assertTransparentPosition($img, 2, 7);
    }

    public function testResizeCanvasLeft()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'left');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 4);
        $this->assertColorAtPosition('#445160', $img, 8, 5);
        $this->assertTransparentPosition($img, 0, 5);
        $this->assertTransparentPosition($img, 8, 4);
    }

    public function testResizeCanvasRight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'right');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 4);
        $this->assertColorAtPosition('#445160', $img, 2, 5);
        $this->assertTransparentPosition($img, 0, 5);
        $this->assertTransparentPosition($img, 2, 4);
    }

    public function testResizeCanvasBottomLeft()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'bottom-left');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 1);
        $this->assertColorAtPosition('#445160', $img, 8, 2);
        $this->assertTransparentPosition($img, 0, 2);
        $this->assertTransparentPosition($img, 8, 1);
    }

    public function testResizeCanvasBottomRight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'bottom-right');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 1);
        $this->assertColorAtPosition('#445160', $img, 2, 2);
        $this->assertTransparentPosition($img, 0, 2);
        $this->assertTransparentPosition($img, 2, 1);
    }

    public function testResizeCanvasBottom()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 10, 'bottom');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 1);
        $this->assertColorAtPosition('#445160', $img, 5, 2);
        $this->assertTransparentPosition($img, 0, 2);
        $this->assertTransparentPosition($img, 5, 1);
    }

    public function testResizeCanvasRelativeWithBackground()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(4, 4, 'center', true, '#ff00ff');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(20, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertColorAtPosition('#ff00ff', $img, 0, 0);
        $this->assertColorAtPosition('#ff00ff', $img, 19, 19);
        $this->assertColorAtPosition('#b4e000', $img, 2, 9);
        $this->assertColorAtPosition('#445160', $img, 10, 10);
        $this->assertTransparentPosition($img, 2, 10);
        $this->assertTransparentPosition($img, 10, 9);
    }

    public function testResizeCanvasJustWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, null);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#445160', $img, 5, 8);
        $this->assertTransparentPosition($img, 0, 8);
        $this->assertTransparentPosition($img, 5, 7);
    }

    public function testResizeCanvasJustHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(null, 10);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 4);
        $this->assertColorAtPosition('#445160', $img, 8, 5);
        $this->assertTransparentPosition($img, 0, 5);
        $this->assertTransparentPosition($img, 8, 4);
    }

    public function testResizeCanvasSmallerWidthLargerHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(10, 20);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 9);
        $this->assertColorAtPosition('#445160', $img, 5, 10);
        $this->assertTransparentPosition($img, 0, 10);
        $this->assertTransparentPosition($img, 5, 9);
    }

    public function testResizeCanvasLargerWidthSmallerHeight()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(20, 10);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(20, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 2, 4);
        $this->assertColorAtPosition('#445160', $img, 10, 5);
        $this->assertTransparentPosition($img, 0, 0);
        $this->assertTransparentPosition($img, 2, 5);
        $this->assertTransparentPosition($img, 10, 4);
    }

    public function testResizeCanvasNegative()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(-4, -4);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(12, $img->getWidth());
        $this->assertEquals(12, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 5);
        $this->assertColorAtPosition('#445160', $img, 6, 6);
        $this->assertTransparentPosition($img, 0, 6);
        $this->assertTransparentPosition($img, 6, 5);
    }

    public function testResizeCanvasLargerHeightAutoWidth()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->resizeCanvas(null, 20, 'bottom-left', false, '#ff00ff');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertColorAtPosition('#ff00ff', $img, 0, 0);
        $this->assertColorAtPosition('#b4e000', $img, 0, 4);
        $this->assertColorAtPosition('#b4e000', $img, 0, 11);
        $this->assertColorAtPosition('#445160', $img, 8, 12);
        $this->assertTransparentPosition($img, 0, 12);
        $this->assertTransparentPosition($img, 8, 11);
    }

    public function testResizeCanvasBorderNonRelative()
    {
        $img = $this->manager()->canvas(1, 1, 'ff0000');
        $img->resizeCanvas(17, 17, 'center', false, '333333');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(17, $img->getWidth());
        $this->assertEquals(17, $img->getHeight());
        $this->assertColorAtPosition('#333333', $img, 0, 0);
        $this->assertColorAtPosition('#333333', $img, 5, 5);
        $this->assertColorAtPosition('#333333', $img, 7, 7);
        $this->assertColorAtPosition('#ff0000', $img, 8, 8);
    }

    public function testCropImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->crop(6, 6); // should be centered without pos.
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(6, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445160', $img, 3, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 3, 2);
    }

    public function testCropImageWithPosition()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->crop(4, 4, 7, 7); // should be centered without pos.
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(4, $img->getWidth());
        $this->assertEquals(4, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 1, 1);
        $this->assertTransparentPosition($img, 0, 1);
        $this->assertTransparentPosition($img, 1, 0);
    }

    public function testFitImageSquare()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->fit(6);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(6, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445060', $img, 3, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 3, 2);
    }

    public function testFitImageRectangle()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->fit(12, 6);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(12, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445160', $img, 6, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 6, 2);
    }

    public function testFitImageWithConstraintUpsize()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->fit(300, 150, function ($constraint) {$constraint->upsize();});
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(25, $img->getHeight());
        $this->assertColorAtPosition('#00aef0', $img, 0, 0);
        $this->assertColorAtPosition('#afa94c', $img, 17, 0);
        $this->assertColorAtPosition('#ffa601', $img, 24, 0);
    }

    public function testFlipImageHorizontal()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->flip('h');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 8, 7);
        $this->assertColorAtPosition('#445160', $img, 0, 8);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testFlipImageVertical()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->flip('v');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 7);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testRotateImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->rotate(90);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 7);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testInsertImage()
    {
        $watermark = $this->manager()->canvas(16, 16, '#0000ff'); // create watermark

        // top-left anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-left', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(16, 16, 'hex'));

        // top-left anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-left', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(9, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(10, 10, 'hex'));

        // top anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 15, 'hex'));

        // top anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(18, 10, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(31, 26, 'hex'));

        // top-right anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-right', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(15, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(31, 0, 'hex'));

        // top-right anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-right', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(6, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(21, 25, 'hex'));

        // left anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'left', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 7, 'hex'));

        // left anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'left', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(8, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(10, 7, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(25, 23, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(25, 8, 'hex'));

        // right anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'right', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(31, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 15, 'hex'));

        // right anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'right', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(5, 8, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(22, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(21, 7, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(6, 8, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(21, 23, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(6, 23, 'hex'));

        // bottom-left anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-left', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 15, 'hex'));

        // bottom-left anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-left', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(10, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(9, 20, 'hex'));

        // bottom anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(8, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 15, 'hex'));

        // bottom anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(5, 8, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(23, 22, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(24, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(7, 6, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(8, 6, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 21, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 6, 'hex'));

        // bottom-right anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-right', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(16, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 16, 'hex'));

        // bottom-right anchor coordinates
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-right', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(21, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(22, 22, 'hex'));

        // center anchor
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'center', 0, 0);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(23, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 7, 'hex'));

        // center anchor coordinates / coordinates will be ignored for center
        $img = $this->manager()->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'center', 10, 10);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(23, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 7, 'hex'));
    }

    public function testInsertWithAlphaChannel()
    {
        $img = $this->manager()->canvas(50, 50, 'ff0000');
        $img->insert('tests/images/circle.png');
        $this->assertColorAtPosition('#ff0000', $img, 0, 0);
        $this->assertColorAtPosition('#320000', $img, 30, 30);
    }

    public function testInsertAfterResize()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->resize(16, 16)->insert('tests/images/tile.png');
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#ffa601', $img, 8, 7);
    }

    public function testInsertResource()
    {
        $resource = imagecreatefrompng('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->insert($resource);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 24, 24);
    }

    public function testInsertBinary()
    {
        $data = file_get_contents('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->insert($data);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 24, 24);
    }

    public function testInsertInterventionImage()
    {
        $obj = $this->manager()->make('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->insert($obj);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 24, 24);
    }

    public function testOpacity()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->opacity(50);
        $checkColor = $img->pickColor(7, 7, 'array');
        $this->assertEquals($checkColor[0], 180);
        $this->assertEquals($checkColor[1], 224);
        $this->assertEquals($checkColor[2], 0);
        $this->assertEquals($checkColor[3], 0.5);
        $checkColor = $img->pickColor(8, 8, 'array');
        $this->assertEquals($checkColor[0], 68);
        $this->assertEquals($checkColor[1], 81);
        $this->assertEquals($checkColor[2], 96);
        $this->assertEquals($checkColor[3], 0.5);
        $this->assertTransparentPosition($img, 0, 11);
    }

    public function testMaskImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->mask('tests/images/gradient.png');
        $this->assertTransparentPosition($img, 0, 0);
        $this->assertTransparentPosition($img, 23, 23);
        $checkColor = $img->pickColor(23, 24, 'array');
        $this->assertEquals($checkColor[0], 255);
        $this->assertEquals($checkColor[1], 166);
        $this->assertEquals($checkColor[2], 1);
        $this->assertEquals($checkColor[3], 0.97);
        $checkColor = $img->pickColor(39, 25, 'array');
        $this->assertEquals($checkColor[0], 0);
        $this->assertEquals($checkColor[1], 174);
        $this->assertEquals($checkColor[2], 240);
        $this->assertEquals($checkColor[3], 0.32);
    }

    public function testMaskImageWithAlpha()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->mask('tests/images/star.png', true);
        $this->assertTransparentPosition($img, 0, 0);
        $this->assertTransparentPosition($img, 16, 16);
        $checkColor = $img->pickColor(18, 18, 'array');
        $this->assertEquals($checkColor[0], 255);
        $this->assertEquals($checkColor[1], 166);
        $this->assertEquals($checkColor[2], 1);
        $this->assertEquals($checkColor[3], 0.65);
        $checkColor = $img->pickColor(24, 10, 'array');
        $this->assertEquals($checkColor[0], 0);
        $this->assertEquals($checkColor[1], 174);
        $this->assertEquals($checkColor[2], 240);
        $this->assertEquals($checkColor[3], 0.80);
    }

    public function testPixelateImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->pixelate(20);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testGreyscaleImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->greyscale();
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertTransparentPosition($img, 8, 0);
        $this->assertColorAtPosition('#b9b9b9', $img, 0, 0);
    }

    public function testInvertImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->invert();
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertTransparentPosition($img, 8, 0);
        $this->assertColorAtPosition('#4b1fff', $img, 0, 0);
    }

    public function testBlurImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->blur(1);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testFillImageWithColor()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->fill('b53717');
        $this->assertColorAtPosition('#b53717', $img, 0, 0);
        $this->assertColorAtPosition('#b53717', $img, 15, 15);
    }

    public function testFillImageWithColorAtPosition()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->fill('b53717', 0, 0);
        $this->assertTransparentPosition($img, 0, 8);
        $this->assertColorAtPosition('#b53717', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 15, 15);
    }

    public function testFillImageWithResource()
    {
        $resource = imagecreatefrompng('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->fill($resource, 0, 0);
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 20, 20);
    }

    public function testFillImageWithBinary()
    {
        $data = file_get_contents('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->fill($data, 0, 0);
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 20, 20);
    }

    public function testFillImageWithInterventionImage()
    {
        $obj = $this->manager()->make('tests/images/tile.png');
        $img = $this->manager()->make('tests/images/trim.png');
        $img->fill($obj, 0, 0);
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 20, 20);
    }

    public function testPixelImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $coords = array(array(5, 5), array(12, 12));
        $img = $img->pixel('fdf5e4', $coords[0][0], $coords[0][1]);
        $img = $img->pixel(array(255, 255, 255), $coords[1][0], $coords[1][1]);
        $this->assertEquals('#fdf5e4', $img->pickColor($coords[0][0], $coords[0][1], 'hex'));
        $this->assertEquals('#ffffff', $img->pickColor($coords[1][0], $coords[1][1], 'hex'));
    }

    public function testTextImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img = $img->text('0', 3, 11);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('a9e2b15452b2a4637b65625188d206f6', $img->checksum());

        
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img = $img->text('0', 8, 2, function($font) {
            $font->align('center');
            $font->valign('top');
            $font->color('000000');
        });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('649f3f529d3931c56601155fd2680959', $img->checksum());
        
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img = $img->text('0', 8, 8, function($font) {
            $font->align('right');
            $font->valign('middle');
            $font->file(2);
            $font->color('000000');
        });
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('c0dda67589c46a90d78a97b891a811ee', $img->checksum());
    }

    public function testRectangleImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img->rectangle(5, 5, 11, 11, function ($draw) { $draw->background('#ff0000'); $draw->border(1, '#0000ff'); });
        $this->assertEquals('e95487dcc29daf371a0e9190bff8dbfe', $img->checksum());
    }

    public function testLineImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img->line(0, 0, 15, 15, function ($draw) { $draw->color('#ff0000'); });
        $this->assertEquals('a6237d34f6e95f30d2fc91a46bd058e6', $img->checksum());
    }

    public function testEllipseImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img->ellipse(12, 8, 8, 8, function ($draw) { $draw->background('#ff0000'); $draw->border(1, '#0000ff'); });
        $this->assertEquals('080d9dd92ebe22f976c3c703cba33510', $img->checksum());
    }

    public function testCircleImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $img->circle(12, 8, 8, function ($draw) { $draw->background('#ff0000'); $draw->border(1, '#0000ff'); });
        $this->assertEquals('c3bff06c20244ba14e898e39ea0efd76', $img->checksum());
    }

    public function testPolygonImage()
    {
        $img = $this->manager()->canvas(16, 16, 'ffffff');
        $points = array(3, 3, 11, 11, 7, 13);
        $img->polygon($points, function ($draw) { $draw->background('#ff0000'); $draw->border(1, '#0000ff'); });
        $this->assertEquals('e534ff90c8026f9317b99071fda01ed4', $img->checksum());
    }

    public function testResetImage()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->backup();
        $img->resize(30, 20);
        $img->reset();
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
    }

    public function testResetEmptyImage()
    {
        $img = $this->manager()->canvas(16, 16, '#0000ff');
        $img->backup();
        $img->resize(30, 20);
        $img->fill('#ff0000');
        $img->reset();
        $this->assertInternalType('int', $img->getWidth());
        $this->assertInternalType('int', $img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#0000ff', $img, 0, 0);
    }

    public function testResetKeepTransparency()
    {
        $img = $this->manager()->make('tests/images/circle.png');
        $img->backup();
        $img->reset();
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testResetToNamed()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->backup('original');
        $img->resize(30, 20);
        $img->backup('30x20');

        // reset to original
        $img->reset('original');
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());

        // reset to 30x20
        // $img->reset('30x20');
        // $this->assertEquals(30, $img->getWidth());
        // $this->assertEquals(20, $img->getHeight());

        // reset to original again
        $img->reset('original');
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
    }

    public function testLimitColors()
    {
       $img = $this->manager()->make('tests/images/trim.png');
       $img->limitColors(4);
       $this->assertLessThanOrEqual(5, imagecolorstotal($img->getCore()));
    }

    public function testLimitColorsKeepTransparency()
    {
        $img = $this->manager()->make('tests/images/star.png');
        $img->limitColors(16);
        $this->assertLessThanOrEqual(17, imagecolorstotal($img->getCore()));
        $this->assertTransparentPosition($img, 0, 0);
        $this->assertColorAtPosition('#0c02b4', $img, 6, 12);
        $this->assertColorAtPosition('#fcbe04', $img, 22, 24);
    }
    
    public function testLimitColorsKeepTransparencyWithMatte()
    {
        $img = $this->manager()->make('tests/images/star.png');
        $img->limitColors(64, '#00ff00');
        $this->assertLessThanOrEqual(65, imagecolorstotal($img->getCore()));
        $this->assertTransparentPosition($img, 0, 0);
        $this->assertColorAtPosition('#04f204', $img, 12, 10);
        $this->assertColorAtPosition('#06fe04', $img, 22, 17);
        $this->assertColorAtPosition('#e40214', $img, 16, 21);
    }

    public function testLimitColorsNullWithMatte()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->limitColors(null, '#ff00ff');
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#ff00ff', $img, 0, 8);
        $this->assertColorAtPosition('#ff00ff', $img, 15, 0);
    }

    public function testPickColorFromTrueColor()
    {
        $img = $this->manager()->make('tests/images/star.png');
        $c = $img->pickColor(0, 0);
        $this->assertEquals(255, $c[0]);
        $this->assertEquals(255, $c[1]);
        $this->assertEquals(255, $c[2]);
        $this->assertEquals(0, $c[3]);

        $c = $img->pickColor(11, 11);
        $this->assertEquals(34, $c[0]);
        $this->assertEquals(0, $c[1]);
        $this->assertEquals(160, $c[2]);
        $this->assertEquals(0.46, $c[3]);

        $c = $img->pickColor(16, 16);
        $this->assertEquals(231, $c[0]);
        $this->assertEquals(0, $c[1]);
        $this->assertEquals(18, $c[2]);
        $this->assertEquals(1, $c[3]);
    }

    public function testPickColorFromIndexed()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $c = $img->pickColor(0, 0);
        $this->assertEquals(180, $c[0]);
        $this->assertEquals(224, $c[1]);
        $this->assertEquals(0, $c[2]);
        $this->assertEquals(1, $c[3]);

        $c = $img->pickColor(8, 8);
        $this->assertEquals(68, $c[0]);
        $this->assertEquals(81, $c[1]);
        $this->assertEquals(96, $c[2]);
        $this->assertEquals(1, $c[3]);

        $c = $img->pickColor(0, 15);
        $this->assertEquals(255, $c[0]);
        $this->assertEquals(255, $c[1]);
        $this->assertEquals(255, $c[2]);
        $this->assertEquals(0, $c[3]);
    }

    public function testPickColorFromPalette()
    {
        $img = $this->manager()->make('tests/images/tile.png');
        $img->limitColors(200);
        
        $c = $img->pickColor(0, 0);
        $this->assertEquals(180, $c[0]);
        $this->assertEquals(226, $c[1]);
        $this->assertEquals(4, $c[2]);
        $this->assertEquals(1, $c[3]);

        $c = $img->pickColor(8, 8);
        $this->assertEquals(68, $c[0]);
        $this->assertEquals(82, $c[1]);
        $this->assertEquals(100, $c[2]);
        $this->assertEquals(1, $c[3]);

        $c = $img->pickColor(0, 15);
        $this->assertEquals(255, $c[0]);
        $this->assertEquals(255, $c[1]);
        $this->assertEquals(255, $c[2]);
        $this->assertEquals(0, $c[3]);
    }

    public function testInterlaceImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->interlace();
        $img->encode('png');
        $this->assertTrue((ord($img->encoded[28]) != '0'));
        $img->interlace(false);
        $img->encode('png');
        $this->assertFalse((ord($img->encoded[28]) != '0'));
    }

    public function testGammaImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->gamma(1.6);
        $this->assertColorAtPosition('#00c9f6', $img, 0, 0);
        $this->assertColorAtPosition('#ffc308', $img, 24, 24);
    }

    public function testBrightnessImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->brightness(35);
        $this->assertColorAtPosition('#59ffff', $img, 0, 0);
        $this->assertColorAtPosition('#ffff5a', $img, 24, 24);
    }

    public function testContrastImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->contrast(35);
        $this->assertColorAtPosition('#00d4ff', $img, 0, 0);
        $this->assertColorAtPosition('#ffc500', $img, 24, 24);
    }

    public function testColorizeImage()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->colorize(40, 25, -50);
        $this->assertColorAtPosition('#66ee70', $img, 0, 0);
        $this->assertColorAtPosition('#ffe600', $img, 24, 24);
    }
    
    public function testTrimGradient()
    {
        $canvas = $this->manager()->make('tests/images/gradient.png');

        $img = clone $canvas;
        $img->trim();
        $this->assertEquals($img->getWidth(), 46);
        $this->assertEquals($img->getHeight(), 46);

        $img = clone $canvas;
        $img->trim(null, null, 10);
        $this->assertEquals($img->getWidth(), 38);
        $this->assertEquals($img->getHeight(), 38);

        $img = clone $canvas;
        $img->trim(null, null, 20);
        $this->assertEquals($img->getWidth(), 34);
        $this->assertEquals($img->getHeight(), 34);

        $img = clone $canvas;
        $img->trim(null, null, 30);
        $this->assertEquals($img->getWidth(), 30);
        $this->assertEquals($img->getHeight(), 30);

        $img = clone $canvas;
        $img->trim(null, null, 40);
        $this->assertEquals($img->getWidth(), 26);
        $this->assertEquals($img->getHeight(), 26);

        $img = clone $canvas;
        $img->trim(null, null, 50);
        $this->assertEquals($img->getWidth(), 22);
        $this->assertEquals($img->getHeight(), 22);

        $img = clone $canvas;
        $img->trim(null, null, 60);
        $this->assertEquals($img->getWidth(), 20);
        $this->assertEquals($img->getHeight(), 20);

        $img = clone $canvas;
        $img->trim(null, null, 70);
        $this->assertEquals($img->getWidth(), 16);
        $this->assertEquals($img->getHeight(), 16);

        $img = clone $canvas;
        $img->trim(null, null, 80);
        $this->assertEquals($img->getWidth(), 12);
        $this->assertEquals($img->getHeight(), 12);

        $img = clone $canvas;
        $img->trim(null, null, 90);
        $this->assertEquals($img->getWidth(), 8);
        $this->assertEquals($img->getHeight(), 8);
    }

    public function testTrimOnlyLeftAndRight()
    {
        $img = $this->manager()->make('tests/images/gradient.png');
        $img->trim(null, array('left', 'right'), 60);
        $this->assertEquals($img->getWidth(), 20);
        $this->assertEquals($img->getHeight(), 50);
    }

    public function testTrimOnlyTopAndBottom()
    {
        $img = $this->manager()->make('tests/images/gradient.png');
        $img->trim(null, array('top', 'bottom'), 60);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 20);
    }

    public function testTrimOnlyTop()
    {
        $img = $this->manager()->make('tests/images/gradient.png');
        $img->trim(null, 'top', 60);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 35);
    }

    public function testTrimOnlyBottom()
    {
        $img = $this->manager()->make('tests/images/gradient.png');
        $img->trim(null, 'top', 60);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 35);
    }

    public function testTrimWithFeather()
    {
        $canvas = $this->manager()->make('tests/images/trim.png');
        
        $img = clone $canvas;
        $feather = 5;
        $img->trim(null, null, null, $feather);
        $this->assertEquals($img->getWidth(), 28 + $feather * 2);
        $this->assertEquals($img->getHeight(), 28 + $feather * 2);

        $img = clone $canvas;
        $feather = 10;
        $img->trim(null, null, null, $feather);
        $this->assertEquals($img->getWidth(), 28 + $feather * 2);
        $this->assertEquals($img->getHeight(), 28 + $feather * 2);

        $img = clone $canvas;
        $feather = 20; // must respect original dimensions of image
        $img->trim(null, null, null, $feather);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 50);

        $img = clone $canvas;
        $feather = -5;
        $img->trim(null, null, null, $feather);
        $this->assertEquals($img->getWidth(), 28 + $feather * 2);
        $this->assertEquals($img->getHeight(), 28 + $feather * 2);

        $img = clone $canvas;
        $feather = -10;
        $img->trim(null, null, null, $feather);
        $this->assertEquals($img->getWidth(), 28 + $feather * 2);
        $this->assertEquals($img->getHeight(), 28 + $feather * 2);

        // trim only left and right with feather
        $img = clone $canvas;
        $feather = 10;
        $img->trim(null, array('left', 'right'), null, $feather);
        $this->assertEquals($img->getWidth(), 28 + $feather * 2);
        $this->assertEquals($img->getHeight(), 50);

        // trim only top and bottom with feather
        $img = clone $canvas;
        $feather = 10;
        $img->trim(null, array('top', 'bottom'), null, $feather);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 28 + $feather * 2);

        // trim with tolerance and feather
        $canvas = $this->manager()->make('tests/images/gradient.png');

        $img = clone $canvas;
        $feather = 2;
        $img->trim(null, null, 10, $feather);
        $this->assertEquals($img->getWidth(), 38 + $feather * 2);
        $this->assertEquals($img->getHeight(), 38 + $feather * 2);

        $img = clone $canvas;
        $feather = 5;
        $img->trim(null, null, 10, $feather);
        $this->assertEquals($img->getWidth(), 38 + $feather * 2);
        $this->assertEquals($img->getHeight(), 38 + $feather * 2);

        $img = clone $canvas;
        $feather = 10; // should respect original dimensions
        $img->trim(null, null, 20, $feather);
        $this->assertEquals($img->getWidth(), 50);
        $this->assertEquals($img->getHeight(), 50);
    }

    public function testEncodeDefault()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->encode();
        $this->assertInternalType('resource', imagecreatefromstring($img->encoded));
    }

    public function testEncodeJpeg()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->encode('jpg');
        $this->assertInternalType('resource', imagecreatefromstring($img->encoded));
    }

    public function testEncodeGif()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->encode('gif');
        $this->assertInternalType('resource', imagecreatefromstring($img->encoded));
    }

    public function testEncodeDataUrl()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->encode('data-url');
        $this->assertEquals('data:image/png;base64', substr($img->encoded, 0, 21));
    }

    public function testExifReadAll()
    {
        $img = $this->manager()->make('tests/images/exif.jpg');
        $data = $img->exif();
        $this->assertInternalType('array', $data);
        $this->assertEquals(19, count($data));
    }

    public function testExifReadKey()
    {
        $img = $this->manager()->make('tests/images/exif.jpg');
        $data = $img->exif('Artist');
        $this->assertInternalType('string', $data);
        $this->assertEquals('Oliver Vogel', $data);
    }

    public function testExifReadNotExistingKey()
    {
        $img = $this->manager()->make('tests/images/exif.jpg');
        $data = $img->exif('xxx');
        $this->assertEquals(null, $data);
    }

    public function testSaveImage()
    {
        $save_as = 'tests/tmp/foo.jpg';
        $img = $this->manager()->make('tests/images/trim.png');
        $img->save($save_as, 80);
        $this->assertFileExists($save_as);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/jpeg');
        @unlink($save_as);

        $save_as = 'tests/tmp/foo.png';
        $img = $this->manager()->make('tests/images/trim.png');
        $img->save($save_as);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.png');
        $this->assertEquals($img->extension, 'png');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/png');
        $this->assertFileExists($save_as);
        @unlink($save_as);

        $save_as = 'tests/tmp/foo.jpg';
        $img = $this->manager()->make('tests/images/trim.png');
        $img->save($save_as, 0);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/jpeg');
        $this->assertFileExists($save_as);
        @unlink($save_as);
    }

    public function testSaveImageWithoutParameter()
    {
        $path = 'tests/tmp/bar.png';

        // create temp. test image (red)
        $img = $this->manager()->canvas(16, 16, '#ff0000');
        $img->save($path);
        $img->destroy();
        
        // open test image again
        $img = $this->manager()->make($path);
        $this->assertColorAtPosition('#ff0000', $img, 0, 0);

        // fill with green and save wthout paramater
        $img->fill('#00ff00');
        $img->save();
        $img->destroy();

        // re-open test image (should be green)
        $img = $this->manager()->make($path);
        $this->assertColorAtPosition('#00ff00', $img, 0, 0);
        $img->destroy();

        @unlink($path);
    }

    public function testDestroy()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->destroy();
        $this->assertEquals(get_resource_type($img->getCore()), 'Unknown');
    }

    public function testStringConversion()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $value = strval($img);
        $this->assertInternalType('string', $value);
    }

    public function testFilter()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $img->filter(new \Intervention\Image\Filters\DemoFilter(10));
        $this->assertColorAtPosition('#818181', $img, 0, 0);
        $this->assertColorAtPosition('#939393', $img, 18, 18);
        $this->assertColorAtPosition('#939393', $img, 18, 18);
        $this->assertColorAtPosition('#adadad', $img, 25, 25);
        $this->assertColorAtPosition('#939393', $img, 35, 35);
    }

    public function testCloneImageObject()
    {
        $img = $this->manager()->make('tests/images/trim.png');
        $cln = clone $img;

        // destroy original
        $img->destroy();
        unset($img);

        // clone should be still intact
        $this->assertInstanceOf('Intervention\Image\Image', $cln);
        $this->assertInternalType('resource', $cln->getCore());
    }

    public function testGifConversionKeepsTransparency()
    {
        $save_as = 'tests/tmp/foo.gif';

        // create gif image from transparent png
        $img = $this->manager()->make('tests/images/star.png');
        $img->save($save_as);

        // new gif image should be transparent
        $img = $this->manager()->make($save_as);
        $this->assertTransparentPosition($img, 0, 0);
        @unlink($save_as);
    }

    private function assertColorAtPosition($color, $img, $x, $y)
    {
        $pick = $img->pickColor($x, $y, 'hex');
        $this->assertEquals($color, $pick);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    private function assertTransparentPosition($img, $x, $y, $transparent = 0)
    {
        // background should be transparent
        $color = $img->pickColor($x, $y, 'array');
        $this->assertEquals($transparent, $color[3]); // alpha channel
    }

    private function manager()
    {
        return new \Intervention\Image\ImageManager(array(
            'driver' => 'gd'
        ));
    }
}
