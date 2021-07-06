<?php

use Intervention\Image\Gd\Shapes\PolygonShape as PolygonGd;
use Intervention\Image\Imagick\Shapes\PolygonShape as PolygonImagick;
use PHPUnit\Framework\TestCase;

class PolygonShapeTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdConstructor()
    {
        $polygon = new PolygonGd([1, 2, 3, 4, 5, 6]);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\PolygonShape', $polygon);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $polygon->points);

    }

    public function testGdApplyToImage()
    {
        $core = imagecreatetruecolor(300, 200);
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $polygon = new PolygonGd([1, 2, 3, 4, 5, 6]);
        $result = $polygon->applyToImage($image);
        $this->assertInstanceOf('Intervention\Image\Gd\Shapes\PolygonShape', $polygon);
        $this->assertTrue($result);
    }

    public function testImagickConstructor()
    {
        $polygon = new PolygonImagick([1, 2, 3, 4, 5, 6]);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\PolygonShape', $polygon);
        $this->assertEquals([
            ['x' => 1, 'y' => 2],
            ['x' => 3, 'y' => 4],
            ['x' => 5, 'y' => 6]],
        $polygon->points);

    }

    public function testImagickApplyToImage()
    {
        $core = Mockery::mock('\Imagick');
        $core->shouldReceive('drawimage')->once();
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($core);
        $polygon = new PolygonImagick([1, 2, 3, 4, 5, 6]);
        $result = $polygon->applyToImage($image);
        $this->assertInstanceOf('Intervention\Image\Imagick\Shapes\PolygonShape', $polygon);
        $this->assertTrue($result);
    }

}
