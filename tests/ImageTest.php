<?php

use Intervention\Image\Image;

class ImageTest extends PHPUnit_Framework_Testcase
{
    protected function setUp()
    {
        
    }

    protected function tearDown()
    {
        
    }

    private function getTestImage()
    {
        return new Image('public/test.jpg');
    }

    public function testConstructorPlain()
    {
        $img = new Image;
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 1);
        $this->assertEquals($img->height, 1);

        $color = $img->pickColor(0, 0, 'array');
        $this->assertInternalType('int', $color['r']);
        $this->assertInternalType('int', $color['g']);
        $this->assertInternalType('int', $color['b']);
        $this->assertInternalType('float', $color['a']);
        $this->assertEquals($color['r'], 0);
        $this->assertEquals($color['g'], 0);
        $this->assertEquals($color['b'], 0);
        $this->assertEquals($color['a'], 0);
    }

    public function testConstructorWithPath()
    {
        $img = new Image('public/test.jpg');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
        $this->assertEquals($img->dirname, 'public');
        $this->assertEquals($img->basename, 'test.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'test');
        $this->assertEquals($img->mime, 'image/jpeg');
    }

    /**
     * @expectedException Intervention\Image\Exception\ImageNotFoundException
     */
    public function testConstructorWithInvalidPath()
    {
        $img = new Image('public/foo/bar/invalid_image_path.jpg');
    }

    /**
     * @expectedException Intervention\Image\Exception\InvalidImageTypeException
     */
    public function testContructorWithPathInvalidType()
    {
        $img = new Image('public/text.txt');
    }

    public function testConstructoWithString()
    {
        $data = file_get_contents('public/test.jpg');
        $img = new Image($data);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    /**
     * @expectedException Intervention\Image\Exception\InvalidImageDataStringException
     */
    public function testConstructionWithInvalidString()
    {
        // the semi-random string is base64_decoded to allow it to
        // pass the isBinary conditional.
        $data = base64_decode('6KKjdeyUAhRPNzxeYybZ');
        $img = new Image($data);
    }

    public function testConstructorWithResource()
    {
        $resource = imagecreatefromjpeg('public/test.jpg');
        $img = new Image($resource);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    /**
     * @expectedException Intervention\Image\Exception\InvalidImageResourceException
     */
    public function testConstructorWithInvalidResource()
    {
        $resource = fopen('public/test.jpg', 'r+');
        $img = new Image($resource);
    }

    public function testConstructorCanvas()
    {
        $img = new Image(null, 800, 600);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);

        $color = $img->pickColor(50, 50, 'array');
        $this->assertInternalType('int', $color['r']);
        $this->assertInternalType('int', $color['g']);
        $this->assertInternalType('int', $color['b']);
        $this->assertInternalType('float', $color['a']);
        $this->assertEquals($color['r'], 0);
        $this->assertEquals($color['g'], 0);
        $this->assertEquals($color['b'], 0);
        $this->assertEquals($color['a'], 0);
    }

    public function testStaticCallMakeFromPath()
    {
        $img = Image::make('public/test.jpg');
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
        $this->assertEquals($img->dirname, 'public');
        $this->assertEquals($img->basename, 'test.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'test');
        $this->assertEquals($img->mime, 'image/jpeg');
    }

    public function testStaticCallMakeFromString()
    {
        $data = file_get_contents('public/test.jpg');
        $img = Image::make($data);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    public function testStaticCallMakeFromResource()
    {
        $resource = imagecreatefromjpeg('public/test.jpg');
        $img = Image::make($resource);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    public function testStaticCallCanvas()
    {
        $img = Image::canvas(300, 200);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);

        $img = Image::canvas(32, 32, 'b53717');
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals($img->pickColor(15, 15, 'hex'), '#b53717');
    }

    public function testStaticCallRaw()
    {
        $data = file_get_contents('public/test.jpg');
        $img = Image::raw($data);
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    public function testCreateCanvasWithTransparentBackground()
    {
        $img = Image::canvas(100, 100);
        $color = $img->pickColor(50, 50, 'array');
        $this->assertInternalType('int', $color['r']);
        $this->assertInternalType('int', $color['g']);
        $this->assertInternalType('int', $color['b']);
        $this->assertInternalType('float', $color['a']);
        $this->assertEquals($color['r'], 0);
        $this->assertEquals($color['g'], 0);
        $this->assertEquals($color['b'], 0);
        $this->assertEquals($color['a'], 0);
    }

    public function testOpenImage()
    {
        $img = new Image;
        $img->open('public/test.jpg');
        $this->assertInternalType('resource', $img->resource);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
        $this->assertEquals($img->dirname, 'public');
        $this->assertEquals($img->basename, 'test.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'test');
        $this->assertEquals($img->mime, 'image/jpeg');
    }

    public function testResizeImage()
    {
        $img = $this->getTestImage();
        $img->resize(320, 240);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);
        $this->assertEquals($img->pickColor(50, 50, 'hex'), '#fffaf4');
        $this->assertEquals($img->pickColor(260, 190, 'hex'), '#ffa600');

        // Only resize the width.
        $img = $this->getTestImage();
        $height = $img->height;
        $img->resize(320);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        // Check if the height is still the same.
        $this->assertEquals($img->height, $height);
        $this->assertEquals($img->pickColor(75, 65, 'hex'), '#fffcf3');
        $this->assertEquals($img->pickColor(250, 150, 'hex'), '#ffc150');

        // Only resize the width.
        $img = $this->getTestImage();
        $width = $img->width;
        $img->resize(null, 240);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        // Check if the width is still the same.
        $this->assertEquals($img->width, $width);
        $this->assertEquals($img->height, 240);
        $this->assertEquals($img->pickColor(150, 75, 'hex'), '#fff4e0');
        $this->assertEquals($img->pickColor(540, 10, 'hex'), '#ffda96');

        // auto height
        $img = $this->getTestImage();
        $img->resize(320, null, true);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);
        $this->assertEquals($img->pickColor(50, 50, 'hex'), '#fffaf4');
        $this->assertEquals($img->pickColor(260, 190, 'hex'), '#ffa600');

        // auto width
        $img = $this->getTestImage();
        $img->resize(null, 240, true);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 320);
        $this->assertEquals($img->height, 240);
        $this->assertEquals($img->pickColor(50, 50, 'hex'), '#fffaf4');
        $this->assertEquals($img->pickColor(260, 190, 'hex'), '#ffa600');

        // preserve simple upsizing
        $img = $this->getTestImage();
        $img->resize(1000, 1000, true, false);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);

        // test dominant width for auto-resizing
        $img = $this->getTestImage();
        $img->resize(1000, 1200, true);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 1000);
        $this->assertEquals($img->height, 750);

        // Test image upsizing.
        $img = $this->getTestImage();
        // Keep original width and height.
        $original_width = $img->width;
        $original_height = $img->height;
        // Increase values a bit.
        $width = $original_width + 500;
        $height = $original_height + 350;
        // Try resizing to higher values while upsizing is set to false.
        $img->resize($width, $height, false, false);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        // Check if width and height are still the same.
        $this->assertEquals($img->width, $original_width);
        $this->assertEquals($img->height, $original_height);
    }

    /**
     * @expectedException Intervention\Image\Exception\DimensionOutOfBoundsException
     */
    public function testResizeImageWithoutDimensions()
    {
        $img = $this->getTestImage();
        $img->resize();
    }

    public function testWidenImage()
    {
        $img = $this->getTestImage();

        $img->widen(100);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 75);

        $img->widen(1000);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 1000);
        $this->assertEquals($img->height, 750);
    }

    public function testHeightenImage()
    {
        $img = $this->getTestImage();

        $img->heighten(150);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 200);
        $this->assertEquals($img->height, 150);

        $img->heighten(900);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 1200);
        $this->assertEquals($img->height, 900);
    }

    public function testResizeCanvas()
    {
        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200); // pin center
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffe8bc', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffaf1c', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'top-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#fee3ae', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'top');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#fffbf2', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffc559', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'top-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffe2ae', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffac12', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#fefdf9', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffca6a', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffca66', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'bottom-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffedcc', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffb42b', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'bottom');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffd179', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(299, 199, 'hex'));

        $img = $this->getTestImage();
        $img->resizeCanvas(300, 200, 'bottom-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#ffb42a', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(299, 199, 'hex'));

        // resize relative from center 5px border in magenta
        $img = $this->getTestImage();
        $img->resizeCanvas(10, 10, 'center', true, 'ff00ff');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 810);
        $this->assertEquals($img->height, 610);
        $this->assertEquals('#ff00ff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ff00ff', $img->pickColor(809, 609, 'hex'));

        // resize just width
        $img = $this->getTestImage();
        $img->resizeCanvas(300, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 600);
        $this->assertEquals('#fffbf2', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(299, 599, 'hex'));

        // resize just height
        $img = $this->getTestImage();
        $img->resizeCanvas(null, 200);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#fefdf9', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(799, 199, 'hex'));

        // smaller width, larger height
        $img = $this->getTestImage();
        $img->resizeCanvas(300, 800);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 300);
        $this->assertEquals($img->height, 800);
        $this->assertEquals('#000000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#000000', $img->pickColor(299, 799, 'hex'));

        // larger width, smaller height
        $img = $this->getTestImage();
        $img->resizeCanvas(900, 200);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 900);
        $this->assertEquals($img->height, 200);
        $this->assertEquals('#000000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#000000', $img->pickColor(899, 199, 'hex'));

        // test negative values (for relative resize)
        $img = $this->getTestImage();
        $img->resizeCanvas(-200, -200);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 600);
        $this->assertEquals($img->height, 400);
        $this->assertEquals('#fffefc', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ffa600', $img->pickColor(599, 399, 'hex'));
    }

    public function testCropImage()
    {
        $img = $this->getTestImage();
        $img->crop(100, 100);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);
        $this->assertEquals('#ffbe46', $img->pickColor(99, 99, 'hex'));

        $img = $this->getTestImage();
        $img->crop(100, 100, 650, 400);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);
        $this->assertEquals('#ffa600', $img->pickColor(99, 99, 'hex'));
    }

    /**
     * @expectedException Intervention\Image\Exception\DimensionOutOfBoundsException
     */
    public function testCropImageWithoutDimensions()
    {
        $img = $this->getTestImage();
        $img->crop(null, null);
    }

    /**
     * @expectedException Intervention\Image\Exception\DimensionOutOfBoundsException
     */
    public function testCropImageWithNonNumericDimensions()
    {
        $img = $this->getTestImage();
        $img->crop('a', 'z');
    }

    public function testLegacyResize()
    {
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
        $this->assertEquals($img->pickColor(50, 50, 'hex'), '#feedcc');
        $this->assertEquals($img->pickColor(140, 20, 'hex'), '#fed891');

        $img = $this->getTestImage();
        $img->grab(200, 100);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 200);
        $this->assertEquals($img->height, 100);
        $this->assertEquals($img->pickColor(50, 25, 'hex'), '#ffeccb');
        $this->assertEquals($img->pickColor(180, 40, 'hex'), '#fead15');

        $img = $this->getTestImage();
        $img->grab(null, 100);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);
        $this->assertEquals($img->pickColor(30, 30, 'hex'), '#fee5b7');
        $this->assertEquals($img->pickColor(95, 20, 'hex'), '#ffbe47');

        $img = $this->getTestImage();
        $img->grab(array('width' => '100'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 100);
        $this->assertEquals($img->height, 100);
        $this->assertEquals($img->pickColor(30, 30, 'hex'), '#fee5b7');
        $this->assertEquals($img->pickColor(95, 20, 'hex'), '#ffbe47');

        $img = $this->getTestImage();
        $img->grab(array('height' => '200'));
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 200);
        $this->assertEquals($img->height, 200);
        $this->assertEquals($img->pickColor(30, 30, 'hex'), '#fff9ed');
        $this->assertEquals($img->pickColor(95, 20, 'hex'), '#ffe8bf');
    }

    /**
     * @expectedException Intervention\Image\Exception\DimensionOutOfBoundsException
     */
    public function testGrabImageWithoutDimensions()
    {
        $img = $this->getTestImage();
        $img->grab();
    }

    public function testFlipImage()
    {
        $img = $this->getTestImage();
        $img->flip('h');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#ffbf47', $img->pickColor(0, 0, 'hex'));

        $img = $this->getTestImage();
        $img->flip('v');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#fed78c', $img->pickColor(0, 0, 'hex'));
    }

    public function testRotateImage()
    {
        $img = $this->getTestImage();
        $img->rotate(90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 600);
        $this->assertEquals($img->height, 800);
        $this->assertEquals('#ffbf47', $img->pickColor(0, 0, 'hex'));

        $img = $this->getTestImage();
        $img->rotate(180);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
        $this->assertEquals('#ffa600', $img->pickColor(0, 0, 'hex'));

        // rotate transparent png and keep transparency
        $img = Image::make('public/circle.png');
        $img->rotate(180);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 50);
        $this->assertEquals($img->height, 50);
        $checkColor = $img->pickColor(0, 0, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
    }

    public function testInsertImage()
    {
        $img = Image::canvas(32, 32, '#ff0000'); // create canvas
        $watermark = Image::canvas(16, 16, '#0000ff'); // create watermark

        // top-left anchor
        $img->insert($watermark, 0, 0, 'top-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(16, 16, 'hex'));
        $img->reset();

        // top-left anchor coordinates
        $img->insert($watermark, 10, 10, 'top-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#ff0000', $img->pickColor(9, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(10, 10, 'hex'));
        $img->reset();

        // top anchor
        $img->insert($watermark, 0, 0, 'top');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#ff0000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 15, 'hex'));
        $img->reset();

        // top anchor coordinates
        $img->insert($watermark, 10, 10, 'top');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(18, 10, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(31, 26, 'hex'));
        $img->reset();

        // top-right anchor
        $img->insert($watermark, 0, 0, 'top-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#ff0000', $img->pickColor(15, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(31, 0, 'hex'));
        $img->reset();

        // top-right anchor coordinates
        $img->insert($watermark, 10, 10, 'top-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#ff0000', $img->pickColor(6, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(21, 25, 'hex'));
        $img->reset();

        // left anchor
        $img->insert($watermark, 0, 0, 'left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 7, 'hex'));
        $img->reset();

        // left anchor coordinates
        $img->insert($watermark, 10, 10, 'left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(25, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(10, 17, 'hex'));
        $img->reset();

        // right anchor
        $img->insert($watermark, 0, 0, 'right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(31, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 15, 'hex'));
        $img->reset();

        // right anchor coordinates
        $img->insert($watermark, 10, 10, 'right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(21, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(5, 18, 'hex'));
        $img->reset();

        // bottom-left anchor
        $img->insert($watermark, 0, 0, 'bottom-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 15, 'hex'));
        $img->reset();

        // bottom-left anchor coordinates
        $img->insert($watermark, 10, 10, 'bottom-left');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(10, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(9, 20, 'hex'));
        $img->reset();

        // bottom anchor
        $img->insert($watermark, 0, 0, 'bottom');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(8, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 15, 'hex'));
        $img->reset();

        // bottom anchor coordinates
        $img->insert($watermark, 10, 10, 'bottom');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(18, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(17, 21, 'hex'));
        $img->reset();

        // bottom-right anchor
        $img->insert($watermark, 0, 0, 'bottom-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(16, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 16, 'hex'));
        $img->reset();

        // bottom-right anchor coordinates
        $img->insert($watermark, 10, 10, 'bottom-right');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(21, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(22, 22, 'hex'));
        $img->reset();

        // center anchor
        $img->insert($watermark, 0, 0, 'center');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(23, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 7, 'hex'));
        $img->reset();

        // center anchor coordinates
        $img->insert($watermark, 10, 10, 'center');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#0000ff', $img->pickColor(31, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(18, 17, 'hex'));
        $img->reset();
    }

    public function testInsertImageFromResource()
    {
        $resource = imagecreatefrompng('public/tile.png');
        $img = Image::canvas(16, 16)->insert($resource);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 16);
        $this->assertEquals($img->height, 16);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(15, 15, 'hex'));
    }

    public function testInsertImageFromBinary()
    {
        $data = file_get_contents('public/tile.png');
        $img = Image::canvas(16, 16)->insert($data);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 16);
        $this->assertEquals($img->height, 16);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(15, 15, 'hex'));
    }

    public function testInsertImageFromObject()
    {
        $obj = new Image('public/tile.png');
        $img = Image::canvas(16, 16)->insert($obj);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 16);
        $this->assertEquals($img->height, 16);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(15, 15, 'hex'));
    }

    public function testInsertImageFromPath()
    {
        $img = Image::canvas(16, 16)->insert('public/tile.png');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 16);
        $this->assertEquals($img->height, 16);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(15, 15, 'hex'));
    }

    public function testOpacity()
    {
        // simple image mask
        $img = Image::make('public/test.jpg');
        $img->resize(32, 32)->opacity(50);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(15, 15, 'array');
        $this->assertEquals($checkColor['r'], 254);
        $this->assertEquals($checkColor['g'], 204);
        $this->assertEquals($checkColor['b'], 112);
        $this->assertEquals($checkColor['a'], 0.5);
        $checkColor = $img->pickColor(31, 31, 'array');
        $this->assertEquals($checkColor['r'], 255);
        $this->assertEquals($checkColor['g'], 166);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0.5);
    }

    /**
     * @expectedException Intervention\Image\Exception\OpacityOutOfBoundsException
     */
    public function testOpacityTooHigh()
    {
        $img = $this->getTestImage();
        $img->opacity(101);
    }

    /**
     * @expectedException Intervention\Image\Exception\OpacityOutOfBoundsException
     */
    public function testOpacityTooLow()
    {
        $img = $this->getTestImage();
        $img->opacity(-1);
    }

    /**
     * @expectedException Intervention\Image\Exception\OpacityOutOfBoundsException
     */
    public function testOpacityAlphaChar()
    {
        $img = $this->getTestImage();
        $img->opacity('a');
    }

    public function testMaskImage()
    {
        // simple image mask
        $img = Image::make('public/test.jpg');
        $img->resize(32, 32)->mask('public/mask1.png', false);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(16, 2, 'array');
        $this->assertEquals($checkColor['r'], 254);
        $this->assertEquals($checkColor['g'], 230);
        $this->assertEquals($checkColor['b'], 186);
        $this->assertEquals($checkColor['a'], 0.83);
        $checkColor = $img->pickColor(31, 31, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);

        // use alpha channel as mask
        $img = Image::make('public/test.jpg');
        $img->resize(32, 32)->mask('public/mask2.png', true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(5, 5, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
        $checkColor = $img->pickColor(20, 15, 'array');
        $this->assertEquals($checkColor['r'], 254);
        $this->assertEquals($checkColor['g'], 190);
        $this->assertEquals($checkColor['b'], 69);
        $this->assertEquals($checkColor['a'], 1);

        // preserve existing alpha channel
        $img = Image::make('public/circle.png');
        $img->resize(32, 32)->mask('public/mask2.png', true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(5, 5, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
        $checkColor = $img->pickColor(15, 15, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0.8);
    }

    public function testMaskWithResource()
    {
        $img = Image::make('public/circle.png');
        $resource = imagecreatefrompng('public/mask2.png');
        $img->resize(32, 32)->mask($resource, true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(5, 5, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
        $checkColor = $img->pickColor(15, 15, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0.8);
    }

    public function testMaskWithBinary()
    {
        $img = Image::make('public/circle.png');
        $data = file_get_contents('public/mask2.png');
        $img->resize(32, 32)->mask($data, true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(5, 5, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
        $checkColor = $img->pickColor(15, 15, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0.8);
    }

    public function testMaskWithObject()
    {
        $img = Image::make('public/circle.png');
        $obj = Image::make('public/mask2.png');
        $img->resize(32, 32)->mask($obj, true);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $checkColor = $img->pickColor(5, 5, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0);
        $checkColor = $img->pickColor(15, 15, 'array');
        $this->assertEquals($checkColor['r'], 0);
        $this->assertEquals($checkColor['g'], 0);
        $this->assertEquals($checkColor['b'], 0);
        $this->assertEquals($checkColor['a'], 0.8);
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
        $this->assertEquals('#adadad', $img->pickColor(660, 450, 'hex'));
    }

    public function testInvertImage()
    {
        $img = $this->getTestImage();
        $img->invert();
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#000000', $img->pickColor(0, 0, 'hex'));
    }

    public function testBlurImage()
    {
        $img = Image::make('public/tile.png')->blur();
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#98bc18', $img->pickColor(0, 7, 'hex'));
    }

    public function testFillImage()
    {
        $img = new Image(null, 32, 32);
        $img = $img->fill('fdf5e4');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#fdf5e4', $img->pickColor(0, 0, 'hex'));

        $img = $img->fill('#fdf5e4');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#fdf5e4', $img->pickColor(0, 0, 'hex'));

        $img = $img->fill('ccc');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#cccccc', $img->pickColor(0, 0, 'hex'));

        $img = $img->fill('#ccc');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#cccccc', $img->pickColor(0, 0, 'hex'));

        $img = $img->fill(array(155, 155, 155), rand(1,10), rand(1,10));
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('#9b9b9b', $img->pickColor(0, 0, 'hex'));

        $img = new Image(null, 32, 32);
        $img = $img->fill('rgba(180, 224, 0, 0.65)', rand(1,10), rand(1,10));
        $checkColor = $img->pickColor(0, 0, 'array');
        $this->assertEquals(180, $checkColor['r']);
        $this->assertEquals(224, $checkColor['g']);
        $this->assertEquals(0, $checkColor['b']);
        $this->assertEquals(0.65, $checkColor['a']);
    }

    public function testFillImageWithResource()
    {
        $resource = imagecreatefrompng('public/tile.png');
        $img = Image::canvas(32, 32)->fill($resource);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(31, 31, 'hex'));
    }

    public function testFillImageWithBinary()
    {
        $data = file_get_contents('public/tile.png');
        $img = Image::canvas(32, 32)->fill($data);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(31, 31, 'hex'));
    }

    public function testFillImageWithObject()
    {
        $obj = new Image('public/tile.png');
        $img = Image::canvas(32, 32)->fill($obj);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(31, 31, 'hex'));
    }

    public function testFillImageWithPath()
    {
        $img = Image::canvas(32, 32)->fill('public/tile.png');
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 32);
        $this->assertEquals($img->height, 32);
        $this->assertEquals('#b4e000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#445160', $img->pickColor(31, 31, 'hex'));
    }

    public function testPixelImage()
    {
        $img = $this->getTestImage();
        $coords = array(array(5, 5), array(100, 100));
        $img = $img->pixel('fdf5e4', $coords[0][0], $coords[0][1]);
        $img = $img->pixel(array(255, 255, 255), $coords[1][0], $coords[1][1]);
        $this->assertEquals('#fdf5e4', $img->pickColor($coords[0][0], $coords[0][1], 'hex'));
        $this->assertEquals('#ffffff', $img->pickColor($coords[1][0], $coords[1][1], 'hex'));   
    }

    public function testTextImage()
    {
        $img = $this->getTestImage();
        $img = $img->text('Fox', 10, 10, 16, '000000', 0, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);

        $img = $img->text('Fox', 10, 10, 16, '#000000', 0, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);

        $img = $img->text('Fox', 10, 10, 16, array(155, 155, 155), 0, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testRectangleImage()
    {
        $img = $this->getTestImage();
        $img = $img->rectangle('cccccc', 10, 10, 100, 100, false);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(10, 10, 'hex'));
        $this->assertEquals('#ffffff', $img->pickColor(50, 50, 'hex'));

        $img = $this->getTestImage();
        $img = $img->rectangle('cccccc', 10, 10, 100, 100, true);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(10, 10, 'hex'));   
        $this->assertEquals('#cccccc', $img->pickColor(50, 50, 'hex'));   
    }

    public function testLineImage()
    {
        $img = $this->getTestImage();
        $img = $img->line('cccccc', 10, 10, 100, 100);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(10, 10, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(100, 100, 'hex'));
    }

    public function testEllipseImage()
    {
        $img = $this->getTestImage();
        $img = $img->ellipse('cccccc', 0, 0, 100, 50, false);
        $img = $img->ellipse('666666', 100, 100, 50, 100, true);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(50, 0, 'hex'));
        $this->assertEquals('#666666', $img->pickColor(100, 100, 'hex'));
    }

    public function testCircleImage()
    {
        $img = $this->getTestImage();
        $img = $img->circle('cccccc', 0, 0, 100, false);
        $img = $img->circle('666666', 100, 100, 50, true);
        $this->assertEquals('#ffffff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(100, 0, 'hex'));
        $this->assertEquals('#cccccc', $img->pickColor(0, 100, 'hex'));
        $this->assertEquals('#666666', $img->pickColor(100, 100, 'hex'));
    }

    public function testInsertImageWithAlphaChannel()
    {
        $img = new Image(null, 50, 50, '#ff0000');
        $img->insert('public/circle.png');
        $this->assertEquals('#ff0000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#320000', $img->pickColor(30, 30, 'hex'));
    }

    public function testInsertPng8WithAlphaChannel()
    {
        $img = new Image(null, 16, 16, '#ff0000');
        $img->insert('public/png8.png');
        $this->assertEquals('#ff0000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#8c8c8c', $img->pickColor(10, 10, 'hex'));
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

    public function testResetEmptyImage()
    {
        $img = new Image(null, 800, 600);
        $img->resize(300, 200);
        $img->reset();
        $this->assertInternalType('int', $img->width);
        $this->assertInternalType('int', $img->height);
        $this->assertEquals($img->width, 800);
        $this->assertEquals($img->height, 600);
    }

    public function testLimitColors()
    {
        // reduce colors
        $img = Image::make('public/test.jpg');
        $img->limitColors(10);
        $this->assertEquals(imagecolorstotal($img->resource), 11);

        // reduce colors + keep transparency with matte
        $img = Image::make('public/mask2.png');
        $img->limitColors(10, '#ff0000'); // red matte
        $this->assertEquals(imagecolorstotal($img->resource), 11);
        $color1 = $img->pickColor(0, 0); // full transparent
        $color2 = $img->pickColor(9, 17); // part of matte gradient
        $this->assertEquals($color1['r'], 255);
        $this->assertEquals($color1['g'], 0);
        $this->assertEquals($color1['b'], 0);
        $this->assertEquals($color1['a'], 0);
        $this->assertEquals($color2['r'], 252);
        $this->assertEquals($color2['g'], 10);
        $this->assertEquals($color2['b'], 11);
        $this->assertEquals($color2['a'], 1);

        // increase colors
        $img = Image::make('public/png8.png');
        $img->limitColors(null); // set image to true color
        $this->assertEquals(imagecolorstotal($img->resource), 0);

        // increase colors + keep transparency with matte
        $img = Image::make('public/png8.png');
        $img->limitColors(null); // set image to true color
        $this->assertEquals(imagecolorstotal($img->resource), 0);
        $color1 = $img->pickColor(0, 0); // full transparent
        $color2 = $img->pickColor(10, 10); // solid color
        $this->assertEquals($color1['r'], 0);
        $this->assertEquals($color1['g'], 0);
        $this->assertEquals($color1['b'], 0);
        $this->assertEquals($color1['a'], 0);
        $this->assertEquals($color2['r'], 140);
        $this->assertEquals($color2['g'], 140);
        $this->assertEquals($color2['b'], 140);
        $this->assertEquals($color2['a'], 1);        
    }

    public function testSaveImage()
    {
        $save_as = 'public/test2.jpg';
        $img = $this->getTestImage();
        $img->save($save_as);
        $this->assertFileExists($save_as);
        @unlink($save_as);

        $save_as = 'public/test2.png';
        $img = $this->getTestImage();
        $img->save($save_as, 80);
        $this->assertFileExists($save_as);
        @unlink($save_as);

        $save_as = 'public/test2.jpg';
        $img = $this->getTestImage();
        $img->save($save_as, 0);
        $this->assertFileExists($save_as);
        @unlink($save_as);
    }

    public function testStringConversion()
    {
        $img = $this->getTestImage();
        $img = strval($img);
        $this->assertInternalType('string', $img);
    }

    public function testPickColor()
    {
        $img = $this->getTestImage();

        // rgb color array (default)
        $color = $img->pickColor(799, 599);
        $this->assertInternalType('array', $color);
        $this->assertInternalType('int', $color['r']);
        $this->assertEquals($color['r'], 255);
        $this->assertInternalType('int', $color['g']);
        $this->assertEquals($color['g'], 166);
        $this->assertInternalType('int', $color['b']);
        $this->assertEquals($color['b'], 0);
        $this->assertInternalType('float', $color['a']);
        $this->assertEquals($color['a'], 1);

        // int color
        $color = $img->pickColor(100, 100, 'int');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 16776956);

        // rgb color string
        $color = $img->pickColor(799, 599, 'rgb');
        $this->assertInternalType('string', $color);
        $this->assertEquals($color, 'rgb(255, 166, 0)');

        // hex color string
        $color = $img->pickColor(799, 599, 'hex');
        $this->assertInternalType('string', $color);
        $this->assertEquals($color, '#ffa600');

        // pick semi-transparent color
        $img = Image::make('public/circle.png');
        $color = $img->pickColor(20, 20, 'array');
        $this->assertInternalType('array', $color);
        $this->assertInternalType('int', $color['r']);
        $this->assertInternalType('int', $color['g']);
        $this->assertInternalType('int', $color['b']);
        $this->assertInternalType('float', $color['a']);
        $this->assertEquals($color['r'], 0);
        $this->assertEquals($color['g'], 0);
        $this->assertEquals($color['b'], 0);
        $this->assertEquals($color['a'], 0.8);
        $color = $img->pickColor(20, 20, 'rgba');
        $this->assertInternalType('string', $color);
        $this->assertEquals($color, 'rgba(0, 0, 0, 0.80)');
    }

    public function testParseColor()
    {
        $img = $this->getTestImage();
        $color = $img->parseColor(array(155, 155, 155));
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 10197915);

        $color = $img->parseColor('#cccccc');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 13421772);

        $color = $img->parseColor('cccccc');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 13421772);

        $color = $img->parseColor('#ccc');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 13421772);

        $color = $img->parseColor('ccc');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 13421772);

        $color = $img->parseColor('rgb(1, 14, 144)');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 69264);

        $color = $img->parseColor('rgb (255, 255, 255)');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 16777215);

        $color = $img->parseColor('rgb(0,0,0)');
        $this->assertInternalType('int', $color);
        $this->assertEquals($color, 0);

        $color = $img->parseColor('rgba(0,0,0,0.5)');
        $this->assertInternalType('int', $color);

        $color = $img->parseColor('rgba(255, 0, 0, 0.5)');
        $this->assertInternalType('int', $color);
    }

    /**
     * @expectedException Intervention\Image\Exception\ImageColorException
     */
    public function testParseColorInvalidRGBColor()
    {
        $img = $this->getTestImage();
        $img->parseColor('rgb()');
    }

    /**
     * @expectedException Intervention\Image\Exception\ImageColorException
     */
    public function testParseColorInvalidHexColor()
    {
        $img = $this->getTestImage();
        $img->parseColor('ab');
    }

    public function testBrightnessImage()
    {
        $img = $this->getTestImage();
        $img->brightness(100);
        $img->brightness(-100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    /**
     * @expectedException Intervention\Image\Exception\BrightnessOutOfBoundsException
     */
    public function testBrightnessOutOfBoundsHigh()
    {
        $img = $this->getTestImage();
        $img->brightness(101);
    }

    /**
     * @expectedException Intervention\Image\Exception\BrightnessOutOfBoundsException
     */
    public function testBrightnessOutOfBoundsLow()
    {
        $img = $this->getTestImage();
        $img->brightness(-101);
    }

    public function testContrastImage()
    {
        $img = $this->getTestImage();
        $img->contrast(100);
        $img->contrast(-100);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    /**
     * @expectedException Intervention\Image\Exception\ContrastOutOfBoundsException
     */
    public function testContrastOutOfBoundsHigh()
    {
        $img = $this->getTestImage();
        $img->contrast(101);
    }

    /**
     * @expectedException Intervention\Image\Exception\ContrastOutOfBoundsException
     */
    public function testContrastOutOfBoundsLow()
    {
        $img = $this->getTestImage();
        $img->contrast(-101);
    }

    public function testEncode()
    {
        // default encoding
        $data = Image::make('public/circle.png')->encode();
        $this->assertInternalType('resource', @imagecreatefromstring($data));

        // jpg encoding
        $data = Image::make('public/circle.png')->encode('jpg');
        $this->assertInternalType('resource', @imagecreatefromstring($data));

        // gif encoding
        $data = Image::make('public/circle.png')->encode('gif');
        $this->assertInternalType('resource', @imagecreatefromstring($data));

        // data-url encoding
        $data = Image::make('public/circle.png')->encode('data-url');
        $encoded = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAChklEQVRo3uXavUscQRjH8c+t2MhhcUhiKolYiDFXCIKFNkoqrYL/gyT/UV4sTZ3CgI2SIhBBsJBwWKjEYCEptLmzSpFi5ny56Hm+nLdjvt3e7t4+v52ZnWee3xTcHyMYxiAG0I8SivF8FUc4xD72sI3KfTy8cMf7y5jAOMZu+R+b2MA6th5ayCSmMXPujd+VKlaxhm/tFjKKOcyiR3s4wRcs40erN3Xd4AHzeIMpdGsf3XiBl/G4cl9CnmABb+PgfShK8aUVsYvaXYQMxlZ4rXOU0YefOL6NkLqIVzrPUBSze5WYribdKS8i6jxHb/wA1FoVstDh7tSsZQr43oqQ+Tiw80pZyBAqzYSMxi5VyrEQeCakN7/rP2QNF8zF5ss7QzFWlwmZFGbsVJiNMf8jZFr70o520BNjviCkLCSAqTETYz8VMuH+stiHpBhjPxUynqAI52PPhJXdWMJCxjCSCcvT1BnOhOQwdQYzoVCQOgOZUO1Inf5M/vOqVihl0pw/Gilmj0AEwjxSfQQ6qpmwSEmdo0yoxabOYSYUlFNnPxOq4qmzlwlr39TZzoRqxGbCIjZRqc8jGwkL2eBsYbUuzfmkGmM/FbIlmCypsRpjv1BFWRNMllQ4iTHjYqXxF54KJksKfMan+kFj0riMnQRE7MRYXdYinNVSp3Iu5B2+NhNCmFeKYuErhyxhsfHHq/yRXcEhyltBewUf3MDoqQmeXZ/gFOWBNbzHwWUnm3mIx7FlenPQMitRxJUJ7nWu7rHg2RU6OGaWYnc6aHZRKz57TfDsjgSn6KGqLjvC12nRNR57q0LqVISU/08cN+3a/XAiTHYfNXxim/HfbqppJPltTpfR0Y1nfwGRl30LQuetpgAAAABJRU5ErkJggg==';
        $this->assertEquals($data, $encoded);
    }

    public function testExifRead()
    {
        // read all data
        $data = Image::make('public/exif.jpg')->exif();
        $this->assertInternalType('array', $data);
        $this->assertEquals(count($data), 19);

        // read key
        $data = Image::make('public/exif.jpg')->exif('Artist');
        $this->assertInternalType('string', $data);
        $this->assertEquals($data, 'Oliver Vogel');

        // read image with no exif data
        $data = Image::make('public/test.jpg')->exif();
        $this->assertEquals($data, null);

        // read key that doesn't exist
        $data = Image::make('public/exif.jpg')->exif('xxx');
        $this->assertEquals($data, null);
    }
}
