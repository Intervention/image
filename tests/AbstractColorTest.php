<?php

use PHPUnit\Framework\TestCase;

class AbstractColorTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     */
    public function testFormatUnknown()
    {
        $this->setExpectedException(\Intervention\Image\Exception\NotSupportedException::class);

        $color = $this->getMockForAbstractClass('\Intervention\Image\AbstractColor');
        $color->format('xxxxxxxxxxxxxxxxxxxxxxx');
    }
}
