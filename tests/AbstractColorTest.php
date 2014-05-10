<?php

class AbstractColorTest extends PHPUnit_Framework_TestCase
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
