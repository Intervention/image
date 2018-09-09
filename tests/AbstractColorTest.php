<?php

use PHPUnit\Framework\TestCase;

class AbstractColorTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotSupportedException
     */
    public function testFormatUnknown()
    {
        $color = $this->getMockForAbstractClass('\Intervention\Image\AbstractColor');
        $color->format('xxxxxxxxxxxxxxxxxxxxxxx');
    }
}
