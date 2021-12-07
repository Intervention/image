<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Interfaces\ColorInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    protected function assertColor($r, $g, $b, $a, ColorInterface $color)
    {
        $this->assertEquals($r, $color->red());
        $this->assertEquals($g, $color->green());
        $this->assertEquals($b, $color->blue());
        $this->assertEquals($a, $color->alpha());
    }

    protected function assertTransparency(ColorInterface $color)
    {
        $this->assertEquals(0, $color->alpha());
    }
}
