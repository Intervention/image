<?php

use Intervention\Image\Size;

class SizeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructor()
    {
        $size = new Size;
        $this->assertInstanceOf('Intervention\Image\Size', $size);
        $this->assertInstanceOf('Intervention\Image\Point', $size->pivot);
        $this->assertEquals(1, $size->width);
        $this->assertEquals(1, $size->height);
    }

    public function testConstructorWithCoordinates()
    {
        $pivot = Mockery::mock('Intervention\Image\Point');
        $size = new Size(300, 200, $pivot);
        $this->assertInstanceOf('Intervention\Image\Size', $size);   
        $this->assertInstanceOf('Intervention\Image\Point', $size->pivot);
        $this->assertEquals(300, $size->width);   
        $this->assertEquals(200, $size->height);   
    }

    public function testGetWidth()
    {
        $size = new Size(800, 600);
        $this->assertEquals(800, $size->getWidth());
    }

    public function testGetHeight()
    {
        $size = new Size(800, 600);
        $this->assertEquals(600, $size->getHeight());
    }

    public function testGetRatio()
    {
        $size = new Size(800, 600);
        $this->assertEquals(1.33333333333, $size->getRatio());

        $size = new Size(100, 100);
        $this->assertEquals(1, $size->getRatio());

        $size = new Size(1920, 1080);
        $this->assertEquals(1.777777777778, $size->getRatio());
    }

    public function testResize()
    {
        $size = new Size(800, 600);
        $size->resize(1000, 2000);
        $this->assertEquals(1000, $size->width);   
        $this->assertEquals(2000, $size->height);   

        $size = new Size(800, 600);
        $size->resize(2000, null);
        $this->assertEquals(2000, $size->width);   
        $this->assertEquals(600, $size->height);   

        $size = new Size(800, 600);
        $size->resize(null, 1000);
        $this->assertEquals(800, $size->width);   
        $this->assertEquals(1000, $size->height);   
    }

    public function testResizeWithCallbackAspectRatio()
    {
        $size = new Size(800, 600);
        $size->resize(1000, 2000, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(1000, $size->width);
        $this->assertEquals(750, $size->height);

        $size = new Size(800, 600);
        $size->resize(2000, 1000, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(1333, $size->width);
        $this->assertEquals(1000, $size->height);

        $size = new Size(800, 600);
        $size->resize(null, 3000, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(4000, $size->width);
        $this->assertEquals(3000, $size->height);

        $size = new Size(800, 600);
        $size->resize(8000, null, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(8000, $size->width);
        $this->assertEquals(6000, $size->height);

        $size = new Size(800, 600);
        $size->resize(100, 400, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(100, $size->width);
        $this->assertEquals(75, $size->height);

        $size = new Size(800, 600);
        $size->resize(400, 100, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(133, $size->width);
        $this->assertEquals(100, $size->height);

        $size = new Size(800, 600);
        $size->resize(null, 300, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(80, null, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(80, $size->width);
        $this->assertEquals(60, $size->height);

        $size = new Size(640, 480);
        $size->resize(225, null, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(225, $size->width);   
        $this->assertEquals(169, $size->height);   

        $size = new Size(640, 480);
        $size->resize(223, null, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(223, $size->width);   
        $this->assertEquals(167, $size->height);   

        $size = new Size(600, 800);
        $size->resize(300, 300, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(225, $size->width);   
        $this->assertEquals(300, $size->height);   

        $size = new Size(800, 600);
        $size->resize(400, 10, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(13, $size->width);   
        $this->assertEquals(10, $size->height);   

        $size = new Size(800, 600);
        $size->resize(1000, 1200, function ($c) { $c->aspectRatio(); });
        $this->assertEquals(1000, $size->width);   
        $this->assertEquals(750, $size->height);   
    }

    public function testResizeWithCallbackUpsize()
    {
        $size = new Size(800, 600);
        $size->resize(1000, 2000, function ($c) { $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(400, 1000, function ($c) { $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(1000, 400, function ($c) { $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(400, $size->height);

        $size = new Size(800, 600);
        $size->resize(400, 300, function ($c) { $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(1000, null, function ($c) { $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(null, 1000, function ($c) { $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);
    }

    public function testResizeWithCallbackAspectRatioAndUpsize()
    {
        $size = new Size(800, 600);
        $size->resize(1000, 2000, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(1000, 600, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(1000, 300, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(400, 1000, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(400, null, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(null, 300, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(400, $size->width);
        $this->assertEquals(300, $size->height);

        $size = new Size(800, 600);
        $size->resize(1000, null, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(null, 1000, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(800, $size->width);
        $this->assertEquals(600, $size->height);

        $size = new Size(800, 600);
        $size->resize(100, 100, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(100, $size->width);
        $this->assertEquals(75, $size->height);

        $size = new Size(800, 600);
        $size->resize(300, 200, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(267, $size->width);
        $this->assertEquals(200, $size->height);

        $size = new Size(600, 800);
        $size->resize(300, 300, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(225, $size->width);   
        $this->assertEquals(300, $size->height);   

        $size = new Size(800, 600);
        $size->resize(400, 10, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $this->assertEquals(13, $size->width);
        $this->assertEquals(10, $size->height);
    }

    public function testRelativePosition()
    {
        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->align('top-left');
        $input->align('top-left');
        $pos = $container->relativePosition($input);
        $this->assertEquals(0, $pos->x);
        $this->assertEquals(0, $pos->y);

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->align('center');
        $input->align('top-left');
        $pos = $container->relativePosition($input);
        $this->assertEquals(400, $pos->x);
        $this->assertEquals(300, $pos->y);

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->align('bottom-right');
        $input->align('top-right');
        $pos = $container->relativePosition($input);
        $this->assertEquals(600, $pos->x);
        $this->assertEquals(600, $pos->y);

        $container = new Size(800, 600);
        $input = new Size(200, 100);
        $container->align('center');
        $input->align('center');
        $pos = $container->relativePosition($input);
        $this->assertEquals(300, $pos->x);
        $this->assertEquals(250, $pos->y);
    }

    public function testAlign()
    {
        $width = 640;
        $height = 480;
        $pivot = Mockery::mock('Intervention\Image\Point');
        $pivot->shouldReceive('setPosition')->with(0, 0)->once();
        $pivot->shouldReceive('setPosition')->with(intval($width/2), 0)->once();
        $pivot->shouldReceive('setPosition')->with($width, 0)->once();
        $pivot->shouldReceive('setPosition')->with(0, intval($height/2))->once();
        $pivot->shouldReceive('setPosition')->with(intval($width/2), intval($height/2))->once();
        $pivot->shouldReceive('setPosition')->with($width, intval($height/2))->once();
        $pivot->shouldReceive('setPosition')->with(0, $height)->once();
        $pivot->shouldReceive('setPosition')->with(intval($width/2), $height)->once();
        $pivot->shouldReceive('setPosition')->with($width, $height)->once();
        
        $box = new Size($width, $height, $pivot);
        $box->align('top-left');
        $box->align('top');
        $box->align('top-right');
        $box->align('left');
        $box->align('center');
        $box->align('right');
        $box->align('bottom-left');
        $box->align('bottom');
        $b = $box->align('bottom-right');
        $this->assertInstanceOf('Intervention\Image\Size', $b);           
    }

    public function testFit()
    {
        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(100, 100));
        $this->assertEquals(600, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals(100, $fitted->pivot->x);
        $this->assertEquals(0, $fitted->pivot->y);

        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(200, 100));
        $this->assertEquals(800, $fitted->width);
        $this->assertEquals(400, $fitted->height);
        $this->assertEquals(0, $fitted->pivot->x);
        $this->assertEquals(100, $fitted->pivot->y);

        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(100, 200));
        $this->assertEquals(300, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals(250, $fitted->pivot->x);
        $this->assertEquals(0, $fitted->pivot->y);

        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(2000, 10));
        $this->assertEquals(800, $fitted->width);
        $this->assertEquals(4, $fitted->height);
        $this->assertEquals(0, $fitted->pivot->x);
        $this->assertEquals(298, $fitted->pivot->y);

        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(10, 2000));
        $this->assertEquals(3, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals(399, $fitted->pivot->x);
        $this->assertEquals(0, $fitted->pivot->y);

        $box = new Size(800, 600);
        $fitted = $box->fit(new Size(800, 600));
        $this->assertEquals(800, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals(0, $fitted->pivot->x);
        $this->assertEquals(0, $fitted->pivot->y);

        $box = new Size(400, 300);
        $fitted = $box->fit(new Size(120, 120));
        $this->assertEquals(300, $fitted->width);
        $this->assertEquals(300, $fitted->height);
        $this->assertEquals(50, $fitted->pivot->x);
        $this->assertEquals(0, $fitted->pivot->y);

        $box = new Size(600, 800);
        $fitted = $box->fit(new Size(100, 100));
        $this->assertEquals(600, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals(0, $fitted->pivot->x);
        $this->assertEquals(100, $fitted->pivot->y);
    }

    /**
     * @dataProvider providerFitWithPosition
     */
    public function testFitWithPosition(Size $box, $position, $x, $y)
    {
        $fitted = $box->fit(new Size(100, 100), $position);
        $this->assertEquals(600, $fitted->width);
        $this->assertEquals(600, $fitted->height);
        $this->assertEquals($x, $fitted->pivot->x);
        $this->assertEquals($y, $fitted->pivot->y);
    }

    public function providerFitWithPosition()
    {
        return array(
            array(new Size(800, 600), 'top-left', 0, 0),
            array(new Size(800, 600), 'top', 100, 0),
            array(new Size(800, 600), 'top-right', 200, 0),
            array(new Size(800, 600), 'left', 0, 0),
            array(new Size(800, 600), 'center', 100, 0),
            array(new Size(800, 600), 'right', 200, 0),
            array(new Size(800, 600), 'bottom-left', 0, 0),
            array(new Size(800, 600), 'bottom', 100, 0),
            array(new Size(800, 600), 'bottom-right', 200, 0),

            array(new Size(600, 800), 'top-left', 0, 0),
            array(new Size(600, 800), 'top', 0, 0),
            array(new Size(600, 800), 'top-right', 0, 0),
            array(new Size(600, 800), 'left', 0, 100),
            array(new Size(600, 800), 'center', 0, 100),
            array(new Size(600, 800), 'right', 0, 100),
            array(new Size(600, 800), 'bottom-left', 0, 200),
            array(new Size(600, 800), 'bottom', 0, 200),
            array(new Size(600, 800), 'bottom-right', 0, 200),
        );
    }

    public function testFitsInto()
    {
        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 1000));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 1000));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(80, 60));
        $this->assertFalse($fits);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testInvalidResize()
    {
        $size = new Size(800, 600);
        $size->resize(null, null);
    }
}
