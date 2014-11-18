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

    public function testLineHeight()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->lineHeight(1.5);
        $this->assertEquals(1.5, $font->lineHeight);
    }

    public function testBox()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $this->assertEquals(null, $font->box);
        $font->box(300, 200);
        $this->assertInstanceOf('Intervention\Image\Size', $font->box);
        $this->assertEquals(300, $font->box->width);
        $this->assertEquals(200, $font->box->height);
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

    public function testGetLines()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');
        $font->text('foo'.PHP_EOL.'bar'.PHP_EOL.'baz');
        $this->assertEquals(array('foo', 'bar', 'baz'), $font->getLines());
    }

    public function testGetWord()
    {
        $font = $this->getMockForAbstractClass('\Intervention\Image\AbstractFont');

        $font->text('foo bar baz');
        $this->assertEquals(array('foo', 'bar', 'baz'), $font->getWords());

        $font->text('foo bar');
        $this->assertEquals(array('foo', 'bar'), $font->getWords());

        $font->text('foo');
        $this->assertEquals(array('foo'), $font->getWords());
    }
}
