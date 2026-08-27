<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\EncodedImageInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(StripMetaModifier::class)]
final class StripMetaModifierTest extends ImagickTestCase
{
    /**
     * Decode the given encoded image again to inspect what the encoder
     * actually wrote instead of what is left on the wand.
     */
    private function decodeAgain(EncodedImageInterface $encoded): Imagick
    {
        $imagick = new Imagick();
        $imagick->readImageBlob((string) $encoded);

        return $imagick;
    }

    public function testApply(): void
    {
        $image = $this->readTestImage('exif.jpg');
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));
        $image->modify(new StripMetaModifier());
        $this->assertNull($image->exif('IFD0.Artist'));
        $result = $image->encodeUsingFormat(Format::JPEG);
        $this->assertArrayNotHasKey('Artist', exif_read_data($result->toStream()));
    }

    public function testApplyAnimated(): void
    {
        $image = $this->createTestAnimation();

        foreach ($image as $key => $frame) {
            $frame->native()->setImageProperty('comment', 'frame ' . $key);
        }

        $image->modify(new StripMetaModifier());

        $this->assertEquals(3, count($image));

        foreach ($image as $key => $frame) {
            $this->assertEmpty(
                $frame->native()->getImageProperty('comment'),
                'Frame ' . $key . ' kept its meta data',
            );
            $this->assertNull(
                $frame->native()->getImageArtifact('png:exclude-chunk'),
                'Frame ' . $key . ' kept the artifact left behind by stripImage()',
            );
        }
    }

    public function testApplyAnimatedKeepsIccProfiles(): void
    {
        $profile = $this->readTestImage('test.jpg')->core()->native()->getImageProfiles('icc')['icc'];

        $image = $this->createTestAnimation();
        foreach ($image as $frame) {
            $frame->native()->setImageProperty('comment', 'strip me');
            $frame->native()->profileImage('icc', $profile);
        }

        $image->modify(new StripMetaModifier());

        // stripImage() drops the profile along with the rest, so the modifier
        // reads it back per frame and re-applies it afterwards
        foreach ($image as $key => $frame) {
            $this->assertEmpty(
                $frame->native()->getImageProperty('comment'),
                'Frame ' . $key . ' kept its meta data',
            );
            $this->assertEquals(
                $profile,
                $frame->native()->getImageProfiles('icc')['icc'] ?? null,
                'Frame ' . $key . ' lost its icc profile',
            );
        }
    }

    public function testApplyKeepsIccProfileInEncodedPng(): void
    {
        $image = $this->readTestImage('test.jpg');
        $profile = $image->core()->native()->getImageProfiles('icc')['icc'];
        $this->assertNotEmpty($profile);

        $image->modify(new StripMetaModifier());

        $result = $this->decodeAgain($image->encode(new PngEncoder()));

        $this->assertEquals($profile, $result->getImageProfiles('icc')['icc'] ?? null);
    }

    public function testApplyKeepsEncoderHintProperties(): void
    {
        // "png:" and "jpeg:" properties are hints for the encoders, the sweep
        // of the property cache has to leave them alone
        $jpeg = $this->readTestImage('exif.jpg');
        $png = $this->readTestImage('rgb.png');
        $this->assertNotEmpty($jpeg->core()->native()->getImageProperty('jpeg:sampling-factor'));
        $this->assertNotEmpty(preg_grep('/^png:/', array_keys($png->core()->native()->getImageProperties())));

        $jpeg->modify(new StripMetaModifier());
        $png->modify(new StripMetaModifier());

        $this->assertNotEmpty(
            $jpeg->core()->native()->getImageProperty('jpeg:sampling-factor'),
            'The sweep removed the jpeg encoder hints',
        );
        $this->assertNotEmpty(
            preg_grep('/^png:/', array_keys($png->core()->native()->getImageProperties())),
            'The sweep removed the png encoder hints',
        );
    }

    public function testApplyRemovesMetaDataFromEncodedPng(): void
    {
        $image = $this->readTestImage('exif.jpg');

        $image->modify(new StripMetaModifier());

        $result = $this->decodeAgain($image->encode(new PngEncoder()));

        $this->assertEmpty(
            preg_grep('/^exif:/', array_keys($result->getImageProperties())),
            'Encoded png carries exif meta data',
        );
    }

    public function testApplyRemovesMetaDataHiddenFromPropertyEnumeration(): void
    {
        // Imagick::getImageProperties() does not report properties whose name
        // starts with "[", but the png encoder writes them out all the same
        $image = $this->readTestImage('test.jpg');
        $image->core()->native()->setImageProperty('[hidden', 'meta data');

        $image->modify(new StripMetaModifier());

        $result = $this->decodeAgain($image->encode(new PngEncoder()));

        $this->assertFalse($result->getImageProperty('[hidden'), 'Encoded png carries hidden meta data');
    }

    public function testApplyDoesNotAffectLaterPngEncoding(): void
    {
        // the modifier runs on the image itself, so anything it leaves behind
        // on the wand is still there when the same image is encoded again
        $image = $this->readTestImage('test.jpg');
        $profile = $image->core()->native()->getImageProfiles('icc')['icc'];
        $this->assertNotEmpty($profile);

        $image->encode(new JpegEncoder(strip: true));

        $result = $this->decodeAgain($image->encode(new PngEncoder()));

        $this->assertEquals($profile, $result->getImageProfiles('icc')['icc'] ?? null);
    }
}
