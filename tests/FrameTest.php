<?php

use Intervention\Image\Frame;

class FrameTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $frame = new Frame('foo');
        $this->assertInstanceOf('Intervention\Image\Frame', $frame);
        $this->assertEquals('foo', $frame->core);
        $this->assertEquals(0, $frame->delay);

        $frame = new Frame('foo', 250);
        $this->assertEquals(250, $frame->delay);
    }
}
