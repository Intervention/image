<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\ContainDownModifier;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ContainDownModifier::class)]
final class ContainDownModifierTest extends BaseTestCase
{
    public function testGetCropSize(): void
    {
        $modifier = new class (300, 200) extends ContainDownModifier
        {
            public function getCropSize(ImageInterface $image): SizeInterface
            {
                return parent::cropSize($image);
            }
        };

        $image = Mockery::mock(ImageInterface::class);
        $size = new Size(300, 200);
        $image->shouldReceive('size')->andReturn($size);
        $this->assertInstanceOf(SizeInterface::class, $modifier->getCropSize($image));
    }
}
