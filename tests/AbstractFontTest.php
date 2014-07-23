<?php

class AbstractFontTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont', array('test'));
        $this->assertEquals('test', $font->text);
    }
    
    public function testText()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->text('test');
        $this->assertEquals('test', $font->text);
    }

    public function testSize()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->size(16);
        $this->assertEquals(16, $font->size);
    }

    public function testColor()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->color('#ffffff');
        $this->assertEquals('#ffffff', $font->color);
    }

    public function testAngle()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->angle(30);
        $this->assertEquals(30, $font->angle);
    }

    public function testAlign()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->align('right');
        $this->assertEquals('right', $font->align);
    }

    public function testValign()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->valign('top');
        $this->assertEquals('top', $font->valign);
    }

    public function testFile()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->file('test.ttf');
        $this->assertEquals('test.ttf', $font->file);
    }

    public function testCountLines()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->text('foo'.PHP_EOL.'bar'.PHP_EOL.'baz');
        $this->assertEquals(3, $font->countLines());   
        $font->text("foo\nbar\nbaz");
        $this->assertEquals(3, $font->countLines());
        $font->text('foo
            bar
            baz');
        $this->assertEquals(3, $font->countLines());
    }
}
