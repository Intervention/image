<?php

use Intervention\Image\Gd\Encoder as GdEncoder;
use Intervention\Image\Imagick\Encoder as ImagickEncoder;

class EncoderTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testProcessJpegGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'jpg', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('image/jpeg; charset=binary', $this->getMime($encoder->result));
    }

    public function testProcessPngGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'png', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('image/png; charset=binary', $this->getMime($encoder->result));
    }

    public function testProcessGifGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'gif', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('image/gif; charset=binary', $this->getMime($encoder->result));
    }

    public function testProcessWebpGd()
    {
        if (function_exists('imagewebp')) {
            $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
            $encoder = new GdEncoder;
            $image = Mockery::mock('\Intervention\Image\Image');
            $image->shouldReceive('getCore')->once()->andReturn($core);
            $image->shouldReceive('setEncoded')->once()->andReturn($image);
            $img = $encoder->process($image, 'webp', 90);
            $this->assertInstanceOf('Intervention\Image\Image', $img);
            $this->assertEquals('image/webp; charset=binary', $this->getMime($encoder->result));
        }
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testProcessTiffGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $img = $encoder->process($image, 'tif', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testProcessBmpGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $img = $encoder->process($image, 'bmp', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testProcessIcoGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $img = $encoder->process($image, 'ico', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testProcessPsdGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $img = $encoder->process($image, 'psd', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
    }

    public function testProcessUnknownWithMimeGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->mime = 'image/jpeg';
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('image/jpeg; charset=binary', $this->getMime($encoder->result));
    }

    public function testProcessUnknownGd()
    {
        $core = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $encoder = new GdEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('image/jpeg; charset=binary', $this->getMime($encoder->result));
    }

    public function testProcessJpegImagick()
    {
        $core = $this->getImagickMock('jpeg');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'jpg', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-jpeg', $encoder->result);
    }

    public function testProcessPngImagick()
    {
        $core = $this->getImagickMock('png');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'png', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-png', $encoder->result);
    }

    public function testProcessGifImagick()
    {
        $core = $this->getImagickMock('gif');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'gif', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-gif', $encoder->result);
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testProcessWebpImagick()
    {
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $img = $encoder->process($image, 'webp', 90);
    }

    public function testProcessTiffImagick()
    {
        $core = $this->getImagickMock('tiff');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'tiff', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-tiff', $encoder->result);
    }

    public function testProcessBmpImagick()
    {
        $core = $this->getImagickMock('bmp');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'bmp', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-bmp', $encoder->result);
    }

    public function testProcessIcoImagick()
    {
        $core = $this->getImagickMock('ico');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'ico', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-ico', $encoder->result);
    }

    public function testProcessPsdImagick()
    {
        $core = $this->getImagickMock('psd');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, 'psd', 90);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-psd', $encoder->result);
    }

    public function testProcessUnknownWithMimeImagick()
    {
        $core = $this->getImagickMock('jpeg');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->mime = 'image/jpeg';
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-jpeg', $encoder->result);
    }

    public function testProcessUnknownImagick()
    {
        $core = $this->getImagickMock('jpeg');
        $encoder = new ImagickEncoder;
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $image->shouldReceive('setEncoded')->once()->andReturn($image);
        $img = $encoder->process($image, null);
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertEquals('mock-jpeg', $encoder->result);
    }

    public function getImagickMock($type)
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('setformat')->with($type)->once();
        $imagick->shouldReceive('setimageformat')->once();
        $imagick->shouldReceive('setcompression')->once();
        $imagick->shouldReceive('setimagecompression')->once();
        $imagick->shouldReceive('setcompressionquality');
        $imagick->shouldReceive('setimagecompressionquality');
        $imagick->shouldReceive('setimagebackgroundcolor');
        $imagick->shouldReceive('setbackgroundcolor');
        $imagick->shouldReceive('mergeimagelayers')->andReturn($imagick);
        $imagick->shouldReceive('getimagesblob')->once()->andReturn(sprintf('mock-%s', $type));
        return $imagick;
    }

    public function getMime($data)
    {
        $finfo = new finfo(FILEINFO_MIME);
        return $finfo->buffer($data);
    }
}
