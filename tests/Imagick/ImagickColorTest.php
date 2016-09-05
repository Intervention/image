<?php

use Intervention\Image\Imagick\Color;

class ImagickColorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGetRedGreenBlueAlphaValue()
    {
        $c = new Color;
        $c->pixel = Mockery::mock('ImagickPixel');
        $c->pixel->shouldReceive('getcolorvalue')->with(Imagick::COLOR_RED)->andReturn(0.956862745098);
        $this->assertEquals(244, $c->getRedValue());

        $c = new Color;
        $c->pixel = Mockery::mock('ImagickPixel');
        $c->pixel->shouldReceive('getcolorvalue')->with(Imagick::COLOR_GREEN)->andReturn(0.0470588235294);
        $this->assertEquals(12, $c->getGreenValue());

        $c = new Color;
        $c->pixel = Mockery::mock('ImagickPixel');
        $c->pixel->shouldReceive('getcolorvalue')->with(Imagick::COLOR_BLUE)->andReturn(0.0862745098039);
        $this->assertEquals(22, $c->getBlueValue());

        $c = new Color;
        $c->pixel = Mockery::mock('ImagickPixel');
        $c->pixel->shouldReceive('getcolorvalue')->with(Imagick::COLOR_ALPHA)->andReturn(1);
        $this->assertEquals(1, $c->getAlphaValue());

        $c = new Color;
        $c->pixel = Mockery::mock('ImagickPixel');
        $c->pixel->shouldReceive('getcolorvalue')->with(Imagick::COLOR_ALPHA)->andReturn(1);
        $this->assertEquals(1, $c->getAlphaValue());
    }

    public function testConstructor()
    {
        $c = new Color;
        $this->validateColor($c, 255, 255, 255, 0);
    }

    public function testParseNull()
    {
        $c = new Color;
        $c->parse(null);
        $this->validateColor($c, 255, 255, 255, 0);
    }

    public function testParseInteger()
    {
        $c = new Color;
        $c->parse(16777215);
        $this->validateColor($c, 255, 255, 255, 0);

        $c = new Color;
        $c->parse(4294967295);
        $this->validateColor($c, 255, 255, 255, 1);
    }

    public function testParseArray()
    {
        $c = new Color;
        $c->parse(array(181, 55, 23, 0.5));
        $this->validateColor($c, 181, 55, 23, 0.5);
    }

    public function testParseHexString()
    {
        $c = new Color;
        $c->parse('#b53717');
        $this->validateColor($c, 181, 55, 23, 1);
    }

    public function testParseRgbaString()
    {
        $c = new Color;
        $c->parse('rgba(181, 55, 23, 1)');
        $this->validateColor($c, 181, 55, 23, 1);
    }

    public function testInitFromInteger()
    {
        $c = new Color;
        $c->initFromInteger(0);
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromInteger(2147483647);
        $this->validateColor($c, 255, 255, 255, 0.5);
        $c->initFromInteger(16777215);
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromInteger(2130706432);
        $this->validateColor($c, 0, 0, 0, 0.5);
        $c->initFromInteger(867514135);
        $this->validateColor($c, 181, 55, 23, 0.2);
    }

    public function testInitFromArray()
    {
        $c = new Color;
        $c->initFromArray(array(0, 0, 0, 0));
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromArray(array(0, 0, 0, 1));
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromArray(array(255, 255, 255, 1));
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromArray(array(255, 255, 255, 0));
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromArray(array(255, 255, 255, 0.5));
        $this->validateColor($c, 255, 255, 255, 0.5);
        $c->initFromArray(array(0, 0, 0));
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromArray(array(255, 255, 255));
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromArray(array(181, 55, 23));
        $this->validateColor($c, 181, 55, 23, 1);
        $c->initFromArray(array(181, 55, 23, 0.5));
        $this->validateColor($c, 181, 55, 23, 0.5);
    }

    public function testInitFromHexString()
    {
        $c = new Color;
        $c->initFromString('#cccccc');
        $this->validateColor($c, 204, 204, 204, 1);
        $c->initFromString('#b53717');
        $this->validateColor($c, 181, 55, 23, 1);
        $c->initFromString('ffffff');
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromString('ff00ff');
        $this->validateColor($c, 255, 0, 255, 1);
        $c->initFromString('#000');
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromString('000');
        $this->validateColor($c, 0, 0, 0, 1);
    }

    public function testInitFromRgbString()
    {
        $c = new Color;
        $c->initFromString('rgb(1, 14, 144)');
        $this->validateColor($c, 1, 14, 144, 1);
        $c->initFromString('rgb (255, 255, 255)');
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromString('rgb(0,0,0)');
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromString('rgba(0,0,0,0)');
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromString('rgba(0,0,0,0.5)');
        $this->validateColor($c, 0, 0, 0, 0.5);
        $c->initFromString('rgba(255, 0, 0, 0.5)');
        $this->validateColor($c, 255, 0, 0, 0.5);
        $c->initFromString('rgba(204, 204, 204, 0.9)');
        $this->validateColor($c, 204, 204, 204, 0.9);
    }

    public function testInitFromRgb()
    {
        $c = new Color;
        $c->initFromRgb(0, 0, 0);
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromRgb(255, 255, 255);
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromRgb(181, 55, 23);
        $this->validateColor($c, 181, 55, 23, 1);
    }

    public function testInitFromRgba()
    {
        $c = new Color;
        $c->initFromRgba(0, 0, 0, 1);
        $this->validateColor($c, 0, 0, 0, 1);
        $c->initFromRgba(255, 255, 255, 1);
        $this->validateColor($c, 255, 255, 255, 1);
        $c->initFromRgba(181, 55, 23, 1);
        $this->validateColor($c, 181, 55, 23, 1);
        $c->initFromRgba(181, 55, 23, 0);
        $this->validateColor($c, 181, 55, 23, 0);
        $c->initFromRgba(181, 55, 23, 0.5);
        $this->validateColor($c, 181, 55, 23, 0.5);
    }

    public function testGetInt()
    {
        $c = new Color;
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 16777215);

        $c = new Color(array(255, 255, 255));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 4294967295);

        $c = new Color(array(255, 255, 255, 1));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 4294967295);

        $c = new Color(array(181, 55, 23, 0.2));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 867514135);

        $c = new Color(array(255, 255, 255, 0.5));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 2164260863);

        $c = new Color(array(181, 55, 23, 1));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 4290066199);

        $c = new Color(array(0, 0, 0, 0));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 0);
    }

    public function testGetHex()
    {
        $c = new Color;
        $i = $c->getHex();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'ffffff');

        $c = new Color(array(255, 255, 255, 1));
        $i = $c->getHex();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'ffffff');

        $c = new Color(array(181, 55, 23, 0.5));
        $i = $c->getHex();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'b53717');

        $c = new Color(array(0, 0, 0, 0));
        $i = $c->getHex('#');
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, '#000000');
    }

    public function testGetArray()
    {
        $c = new Color;
        $i = $c->getArray();
        $this->assertInternalType('array', $i);
        $this->assertEquals($i, array(255, 255, 255, 0));

        $c = new Color(array(255, 255, 255, 1));
        $i = $c->getArray();
        $this->assertInternalType('array', $i);
        $this->assertEquals($i, array(255, 255, 255, 1));

        $c = new Color(array(181, 55, 23, 0.5));
        $i = $c->getArray();
        $this->assertInternalType('array', $i);
        $this->assertEquals($i, array(181, 55, 23, 0.5));

        $c = new Color(array(0, 0, 0, 1));
        $i = $c->getArray();
        $this->assertInternalType('array', $i);
        $this->assertEquals($i, array(0, 0, 0, 1));
    }

    public function testGetRgba()
    {
        $c = new Color;
        $i = $c->getRgba();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'rgba(255, 255, 255, 0.00)');

        $c = new Color(array(255, 255, 255, 1));
        $i = $c->getRgba();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'rgba(255, 255, 255, 1.00)');

        $c = new Color(array(181, 55, 23, 0.5));
        $i = $c->getRgba();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'rgba(181, 55, 23, 0.50)');

        $c = new Color(array(0, 0, 0, 1));
        $i = $c->getRgba();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'rgba(0, 0, 0, 1.00)');

        $c = new Color(array(255, 255, 255, 0.5));
        $i = $c->getRgba();
        $this->assertInternalType('string', $i);
        $this->assertEquals($i, 'rgba(255, 255, 255, 0.50)');
    }

    public function testDiffers()
    {
        $c1 = new Color(array(0, 0, 0));
        $c2 = new Color(array(0, 0, 0));
        $this->assertEquals(false, $c1->differs($c2));

        $c1 = new Color(array(1, 0, 0));
        $c2 = new Color(array(0, 0, 0));
        $this->assertEquals(true, $c1->differs($c2));

        $c1 = new Color(array(1, 0, 0));
        $c2 = new Color(array(0, 0, 0));
        $this->assertEquals(false, $c1->differs($c2, 10));

        $c1 = new Color(array(127, 127, 127));
        $c2 = new Color(array(0, 0, 0));
        $this->assertEquals(true, $c1->differs($c2, 49));

        $c1 = new Color(array(127, 127, 127));
        $c2 = new Color(array(0, 0, 0));
        $this->assertEquals(false, $c1->differs($c2, 50));
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotReadableException
     */
    public function testParseUnknown()
    {
        $c = new Color('xxxxxxxxxxxxxxxxxxxx');
    }

    private function validateColor($obj, $r, $g, $b, $a)
    {
        $this->assertInstanceOf('Intervention\Image\Imagick\Color', $obj);
        $this->assertInstanceOf('ImagickPixel', $obj->pixel);
        $this->assertEquals($r, round($obj->getRedValue(), 2));
        $this->assertEquals($g, round($obj->getGreenValue(), 2));
        $this->assertEquals($b, round($obj->getBlueValue(), 2));
        $this->assertEquals($a, round($obj->getAlphaValue(), 2));
    }
}
