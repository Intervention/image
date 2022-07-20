<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Abstract\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier;
use Intervention\Image\Drivers\Imagick\ImageFactory;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier
 */
class AbstractFitModifierTest extends TestCase
{
    public function providerCropSize(): iterable
    {
        yield '150x100' => [150, 100, 50, 100, 50, 0];
        yield '100x150' => [100, 150, 75, 150, 13, 0];
    }

    /** @dataProvider providerCropSize */
    public function testGetCropSize(int $width, int $height, int $expectedWidth, int $expectedHeight, int $expectedX, int $expectedY): void
    {
        $modifier = $this->getModifier(100, 200, 'center');

        $image = (new ImageFactory())->newImage($width, $height);
        $size = $modifier->getCropSize($image);

        static::assertSame($expectedWidth, $size->getWidth());
        static::assertSame($expectedHeight, $size->getHeight());
        static::assertSame($expectedX, $size->getPivot()->getX());
        static::assertSame($expectedY, $size->getPivot()->getY());
    }

    public function testGetResizeSize(): void
    {
        $modifier = $this->getModifier(200, 100, 'center');

        $image = (new ImageFactory())->newImage(300, 200);
        $size = $modifier->getCropSize($image);
        $resize = $modifier->getResizeSize($size);

        static::assertSame(200, $resize->getWidth());
        static::assertSame(100, $resize->getHeight());
        static::assertSame(0, $resize->getPivot()->getX());
        static::assertSame(0, $resize->getPivot()->getY());
    }

    private function getModifier(int $width, int $height, string $position): AbstractFitModifier
    {
        return new class ($width, $height, $position) extends AbstractFitModifier {
            public function getCropSize(ImageInterface $image): SizeInterface
            {
                return parent::getCropSize($image);
            }

            public function getResizeSize(SizeInterface $size): SizeInterface
            {
                return parent::getResizeSize($size);
            }
        };
    }
}
