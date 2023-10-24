<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Abstract\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier;
use Intervention\Image\Drivers\Imagick\ImageFactory;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Abstract\Modifiers\AbstractPadModifier
 *
 * @internal
 */
final class AbstractPadModifierTest extends TestCase
{
    public function providerCropSize(): iterable
    {
        yield '150x100' => [150, 100, 100, 67, 0, 67];
        yield '100x150' => [100, 150, 100, 150, 0, 25];
    }

    /** @dataProvider providerCropSize */
    public function testGetCropSize(int $width, int $height, int $expectedWidth, int $expectedHeight, int $expectedX, int $expectedY): void
    {
        $modifier = $this->getModifier(100, 200, 'ffffff', 'center');

        $image = (new ImageFactory())->newImage($width, $height);
        $size = $modifier->getCropSize($image);

        static::assertSame($expectedWidth, $size->width());
        static::assertSame($expectedHeight, $size->height());
        static::assertSame($expectedX, $size->getPivot()->getX());
        static::assertSame($expectedY, $size->getPivot()->getY());
    }

    public function testGetResizeSize(): void
    {
        $modifier = $this->getModifier(200, 100, 'ffffff', 'center');

        $image = (new ImageFactory())->newImage(300, 200);
        $resize = $modifier->getResizeSize($image);

        static::assertSame(200, $resize->width());
        static::assertSame(100, $resize->height());
        static::assertSame(0, $resize->getPivot()->getX());
        static::assertSame(0, $resize->getPivot()->getY());
    }

    private function getModifier(int $width, int $height, $background, string $position): AbstractPadModifier
    {
        return new class($width, $height, $background, $position) extends AbstractPadModifier {
            public function getCropSize(ImageInterface $image): SizeInterface
            {
                return parent::getCropSize($image);
            }

            public function getResizeSize(ImageInterface $image): SizeInterface
            {
                return parent::getResizeSize($image);
            }
        };
    }
}
