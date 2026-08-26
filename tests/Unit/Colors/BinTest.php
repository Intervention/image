<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Color;
use Intervention\Image\Colors\Bin;
use Intervention\Image\Tests\BaseTestCase;

class BinTest extends BaseTestCase
{
    public function testIncreateCount(): void
    {
        $bin = new Bin(Color::rgb(0, 0, 0));
        $this->assertEquals(0, $bin->count);
        $bin->increaseCount();
        $this->assertEquals(1, $bin->count);
        $bin->increaseCount(4);
        $this->assertEquals(5, $bin->count);
    }
}
