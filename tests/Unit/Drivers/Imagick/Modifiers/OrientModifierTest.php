<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Modifiers\OrientModifier as ImagickOrientModifier;
use Intervention\Image\Image;
use Intervention\Image\Modifiers\OrientModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(OrientModifier::class)]
#[CoversClass(ImagickOrientModifier::class)]
final class OrientModifierTest extends ImagickTestCase
{
    public function testApplyAnimatedRotates(): void
    {
        $image = $this->createOrientedTestAnimation(Imagick::ORIENTATION_RIGHTTOP);

        $image->modify(new OrientModifier());

        for ($key = 0; $key < count($image); $key++) {
            $frame = $image->core()->frame($key);
            $this->assertEquals(1, $frame->size()->width(), 'Frame ' . $key . ' was not rotated');
            $this->assertEquals(2, $frame->size()->height(), 'Frame ' . $key . ' was not rotated');
            $this->assertEquals(
                Imagick::ORIENTATION_TOPLEFT,
                $frame->native()->getImageOrientation(),
                'Frame ' . $key . ' was not marked as aligned',
            );
        }
    }

    public function testApplyAnimatedFlops(): void
    {
        $image = $this->createOrientedTestAnimation(Imagick::ORIENTATION_TOPRIGHT);

        $image->modify(new OrientModifier());

        // every frame is red on the left and blue on the right, so a
        // horizontal mirror has to swap the two pixels of each of them
        for ($key = 0; $key < count($image); $key++) {
            $this->assertEquals('0000ff', $image->colorAt(0, 0, $key)->toHex(), 'Frame ' . $key . ' left');
            $this->assertEquals('ff0000', $image->colorAt(1, 0, $key)->toHex(), 'Frame ' . $key . ' right');
            $this->assertEquals(
                Imagick::ORIENTATION_TOPLEFT,
                $image->core()->frame($key)->native()->getImageOrientation(),
                'Frame ' . $key . ' was not marked as aligned',
            );
        }
    }

    /**
     * Create a two frame 2x1 animation, every frame red on the left and blue
     * on the right, carrying the given orientation.
     */
    private function createOrientedTestAnimation(int $orientation): Image
    {
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        for ($i = 0; $i < 2; $i++) {
            $frame = new Imagick();
            $frame->newImage(2, 1, new ImagickPixel('black'), 'gif');
            $frame->importImagePixels(0, 0, 2, 1, 'RGB', Imagick::PIXEL_CHAR, [255, 0, 0, 0, 0, 255]);
            $frame->setImageDelay(10);
            $frame->setImageOrientation($orientation);
            $imagick->addImage($frame);
        }

        $imagick->resetIterator();

        return new Image(new Driver(), new Core($imagick));
    }
}
