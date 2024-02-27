<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\PadModifier;
use Intervention\Image\Tests\TestCase;
use Mockery;

final class PadModifierTest extends TestCase
{
    public function testGetCropSize(): void
    {
        $modifier = new PadModifier(300, 200);
        $image = Mockery::mock(ImageInterface::class);
        $size = new Rectangle(300, 200);
        $image->shouldReceive('size')->andReturn($size);
        $this->assertInstanceOf(SizeInterface::class, $modifier->getCropSize($image));
    }
}
