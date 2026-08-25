<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\Format;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(StripMetaModifier::class)]
final class StripMetaModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('exif.jpg');
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));
        $image->modify(new StripMetaModifier());
        $this->assertNull($image->exif('IFD0.Artist'));
        $result = $image->encodeUsingFormat(Format::JPEG);
        $this->assertEmpty(exif_read_data($result->toStream())['IFD0.Artist'] ?? null);
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
}
