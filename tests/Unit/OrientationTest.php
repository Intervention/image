<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Orientation;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Orientation::class)]
class OrientationTest extends BaseTestCase
{
    public function testFromSize(): void
    {
        $this->assertEquals(Orientation::LANDSCAPE, Orientation::fromSize(new Size(300, 200)));
        $this->assertEquals(Orientation::PORTRAIT, Orientation::fromSize(new Size(200, 300)));
        $this->assertEquals(Orientation::SQUARE, Orientation::fromSize(new Size(300, 300)));
    }
}
