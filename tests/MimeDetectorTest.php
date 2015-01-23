<?php

use Intervention\Image\MimeDetector as Detector;

class MimeDetectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Intervention\Image\Exception\NotSupportedException
     */
    public function testNullValue()
    {
        $detector = new Detector;
        $detector->getMimeType();
    }

    public function testDetectJpeg()
    {
        $detector = new Detector(file_get_contents('tests/images/test.jpg'));
        $this->assertEquals('image/jpeg', $detector->getMimeType());
    }

    public function testDetectPng()
    {
        $detector = new Detector(file_get_contents('tests/images/tile.png'));
        $this->assertEquals('image/png', $detector->getMimeType());
    }

    public function testDetectGif()
    {
        $detector = new Detector(file_get_contents('tests/images/animation.gif'));
        $this->assertEquals('image/gif', $detector->getMimeType());
    }

    public function testDetectBitmap()
    {
        $detector = new Detector(file_get_contents('tests/images/test.bmp'));
        $this->assertEquals('image/bmp', $detector->getMimeType());
    }

    public function testDetectIco()
    {
        $detector = new Detector(file_get_contents('tests/images/test.ico'));
        $this->assertEquals('image/x-icon', $detector->getMimeType());
    }

    public function testDetectWebp()
    {
        $detector = new Detector(file_get_contents('tests/images/test.webp'));
        $this->assertEquals('image/webp', $detector->getMimeType());
    }
}
