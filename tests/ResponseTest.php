<?php

use \Intervention\Image\Response;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $image = Mockery::mock('\Intervention\Image\Image');
        $response = new Response($image);
        $this->assertInstanceOf('\Intervention\Image\Response', $response);
        $this->assertInstanceOf('\Intervention\Image\Image', $response->image);
    }

    public function testConstructorWithParameters()
    {
        $image = Mockery::mock('\Intervention\Image\Image');
        $response = new Response($image, 'jpg', 75);
        $this->assertInstanceOf('\Intervention\Image\Response', $response);
        $this->assertInstanceOf('\Intervention\Image\Image', $response->image);
        $this->assertEquals('jpg', $response->format);
        $this->assertEquals(75, $response->quality);
    }
}
