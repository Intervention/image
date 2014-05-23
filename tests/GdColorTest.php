<?php

use Intervention\Image\Gd\Color;

// alpha - A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.

class GdColorTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $c = new Color;
        $this->validateColor($c, 255, 255, 255, 127);
    }

    public function testParseNull()
    {
        $c = new Color;
        $c->parse(null);
        $this->validateColor($c, 255, 255, 255, 127);
    }

    public function testParseInteger()
    {
        $c = new Color;
        $c->parse(850736919);
        $this->validateColor($c, 181, 55, 23, 50);
    }

    public function testParseArray()
    {
        $c = new Color;
        $c->parse(array(181, 55, 23, 0.5));
        $this->validateColor($c, 181, 55, 23, 64);
    }

    public function testParseHexString()
    {
        $c = new Color;
        $c->parse('#b53717');
        $this->validateColor($c, 181, 55, 23, 0);
    }

    public function testParseRgbaString()
    {
        $c = new Color;
        $c->parse('rgba(181, 55, 23, 1)');
        $this->validateColor($c, 181, 55, 23, 0);
    }

    public function testInitFromInteger()
    {
        $c = new Color;
        $c->initFromInteger(0);
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromInteger(2147483647);
        $this->validateColor($c, 255, 255, 255, 127);
        $c->initFromInteger(16777215);
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromInteger(2130706432);
        $this->validateColor($c, 0, 0, 0, 127);
        $c->initFromInteger(850736919);
        $this->validateColor($c, 181, 55, 23, 50);
    }

    public function testInitFromArray()
    {
        $c = new Color;
        $c->initFromArray(array(0, 0, 0, 0));
        $this->validateColor($c, 0, 0, 0, 127);
        $c->initFromArray(array(0, 0, 0, 1));
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromArray(array(255, 255, 255, 1));
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromArray(array(255, 255, 255, 0));
        $this->validateColor($c, 255, 255, 255, 127);
        $c->initFromArray(array(255, 255, 255, 0.5));
        $this->validateColor($c, 255, 255, 255, 64);
        $c->initFromArray(array(0, 0, 0));
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromArray(array(255, 255, 255));
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromArray(array(181, 55, 23));
        $this->validateColor($c, 181, 55, 23, 0);
        $c->initFromArray(array(181, 55, 23, 0.5));
        $this->validateColor($c, 181, 55, 23, 64);
    }

    public function testInitFromHexString()
    {
        $c = new Color;
        $c->initFromString('#cccccc');
        $this->validateColor($c, 204, 204, 204, 0);
        $c->initFromString('#b53717');
        $this->validateColor($c, 181, 55, 23, 0);
        $c->initFromString('ffffff');
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromString('ff00ff');
        $this->validateColor($c, 255, 0, 255, 0);
        $c->initFromString('#000');
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromString('000');
        $this->validateColor($c, 0, 0, 0, 0);
    }

    public function testInitFromRgbString()
    {
        $c = new Color;
        $c->initFromString('rgb(1, 14, 144)');
        $this->validateColor($c, 1, 14, 144, 0);
        $c->initFromString('rgb (255, 255, 255)');
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromString('rgb(0,0,0)');
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromString('rgba(0,0,0,0)');
        $this->validateColor($c, 0, 0, 0, 127);
        $c->initFromString('rgba(0,0,0,0.5)');
        $this->validateColor($c, 0, 0, 0, 64);
        $c->initFromString('rgba(255, 0, 0, 0.5)');
        $this->validateColor($c, 255, 0, 0, 64);
        $c->initFromString('rgba(204, 204, 204, 0.9)');
        $this->validateColor($c, 204, 204, 204, 13);
    }

    public function testInitFromRgb()
    {
        $c = new Color;
        $c->initFromRgb(0, 0, 0);
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromRgb(255, 255, 255);
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromRgb(181, 55, 23);
        $this->validateColor($c, 181, 55, 23, 0);
    }

    public function testInitFromRgba()
    {
        $c = new Color;
        $c->initFromRgba(0, 0, 0, 1);
        $this->validateColor($c, 0, 0, 0, 0);
        $c->initFromRgba(255, 255, 255, 1);
        $this->validateColor($c, 255, 255, 255, 0);
        $c->initFromRgba(181, 55, 23, 1);
        $this->validateColor($c, 181, 55, 23, 0);
        $c->initFromRgba(181, 55, 23, 0);
        $this->validateColor($c, 181, 55, 23, 127);
        $c->initFromRgba(181, 55, 23, 0.5);
        $this->validateColor($c, 181, 55, 23, 64);
    }

    public function testGetInt()
    {
        $c = new Color;
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals(2147483647, $i);

        $c = new Color(array(255, 255, 255));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 16777215);

        $c = new Color(array(255, 255, 255, 1));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 16777215);

        $c = new Color(array(181, 55, 23, 0.5));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 1085617943);

        $c = new Color(array(181, 55, 23, 1));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 11876119);

        $c = new Color(array(0, 0, 0, 0));
        $i = $c->getInt();
        $this->assertInternalType('int', $i);
        $this->assertEquals($i, 2130706432);
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
        $this->assertInstanceOf('Intervention\Image\Gd\Color', $obj);
        $this->assertInternalType('int', $r);
        $this->assertInternalType('int', $g);
        $this->assertInternalType('int', $b);
        $this->assertInternalType('int', $a);
        $this->assertEquals($obj->r, $r);
        $this->assertEquals($obj->g, $g);
        $this->assertEquals($obj->b, $b);
        $this->assertEquals($obj->a, $a);
    }
}
