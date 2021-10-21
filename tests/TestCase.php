<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Interfaces\ColorInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected function assertColor($r, $g, $b, $a, ColorInterface $color)
    {
        $this->assertEquals($r, $color->getRgbRed());
        $this->assertEquals($g, $color->getRgbGreen());
        $this->assertEquals($b, $color->getRgbBlue());
        $this->assertEquals($a, $color->getOpacity());
    }
}
