<?php

use Intervention\Image\Gd\Gif\Encoder as Encoder;

class GifEncodeDecodeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
}
