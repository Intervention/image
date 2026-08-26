<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Imagick;
use ImagickPixel;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Modifiers\GrayscaleModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\GrayscaleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\GrayscaleModifier::class)]
final class GrayscaleModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->colorAt(0, 0)->isGrayscale());
        $image->modify(new GrayscaleModifier());
        $this->assertTrue($image->colorAt(0, 0)->isGrayscale());
    }

    public function testSaturatedColorsKeepDistinctBrightness(): void
    {
        // blocks.png holds pure blue, green and red in three of its quadrants
        $image = $this->readTestImage('blocks.png')->modify(new GrayscaleModifier());

        $blue = $image->colorAt(160, 120)->channel(Red::class)->value();
        $green = $image->colorAt(160, 360)->channel(Red::class)->value();
        $red = $image->colorAt(480, 360)->channel(Red::class)->value();

        // any luma based conversion keeps blue darkest and green brightest
        $this->assertLessThan($red, $blue);
        $this->assertLessThan($green, $red);
    }

    public function testColorspaceIsPreserved(): void
    {
        $image = $this->readTestImage('cmyk.jpg');
        $this->assertInstanceOf(CmykColorspace::class, $image->colorspace());

        $image->modify(new GrayscaleModifier());

        // grayscale is a color operation, it has no business moving a cmyk
        // source to another colorspace
        $this->assertEquals(
            Imagick::COLORSPACE_CMYK,
            $image->core()->frame(0)->native()->getImageColorspace(),
        );

        // the grey has to sit in the black channel, the three ink channels
        // carry no color anymore
        $color = $image->colorAt(0, 0);
        $this->assertEquals(0, $color->channel(Cyan::class)->value());
        $this->assertEquals(0, $color->channel(Magenta::class)->value());
        $this->assertEquals(0, $color->channel(Yellow::class)->value());
        $this->assertEqualsWithDelta(43, $color->channel(Key::class)->value(), 2);
    }

    public function testColorspaceIsPreservedOnEveryFrame(): void
    {
        // the frames deliberately do not share a colorspace, an implementation
        // that reads the colorspace once outside the frame loop, or that
        // hardcodes one, cannot satisfy this
        $imagick = new Imagick();
        $imagick->setFormat('tiff');

        foreach ([Imagick::COLORSPACE_SRGB, Imagick::COLORSPACE_CMYK] as $colorspace) {
            $frame = new Imagick();
            $frame->newImage(4, 4, new ImagickPixel('red'), 'tiff');
            $frame->transformImageColorspace($colorspace);
            $frame->setImageDelay(10);
            $imagick->addImage($frame);
        }

        $imagick->setFirstIterator();
        $image = new Image(new Driver(), new Core($imagick));

        $image->modify(new GrayscaleModifier());

        foreach ([Imagick::COLORSPACE_SRGB, Imagick::COLORSPACE_CMYK] as $key => $colorspace) {
            $this->assertEquals(
                $colorspace,
                $image->core()->frame($key)->native()->getImageColorspace(),
                'Frame ' . $key . ' did not keep its colorspace',
            );
        }
    }
}
