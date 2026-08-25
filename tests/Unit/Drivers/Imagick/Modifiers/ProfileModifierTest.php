<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Colors\Profile;
use Intervention\Image\Drivers\Imagick\Modifiers\ProfileModifier as ImagickProfileModifier;
use Intervention\Image\Modifiers\ProfileModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(ProfileModifier::class)]
#[CoversClass(ImagickProfileModifier::class)]
final class ProfileModifierTest extends ImagickTestCase
{
    /**
     * Read the icc profile embedded in one of the test resources.
     */
    private function getTestProfile(): Profile
    {
        return new Profile(
            $this->readTestImage('test.jpg')->core()->native()->getImageProfiles('icc')['icc'],
        );
    }

    public function testApply(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertEmpty($image->core()->native()->getImageProfiles('icc'));

        $image->modify(new ProfileModifier($this->getTestProfile()));

        $this->assertNotEmpty($image->core()->native()->getImageProfiles('icc'));
    }

    public function testApplyAnimated(): void
    {
        $image = $this->createTestAnimation();
        $image->modify(new ProfileModifier($this->getTestProfile()));

        foreach ($image as $key => $frame) {
            $this->assertNotEmpty(
                $frame->native()->getImageProfiles('icc'),
                'Frame ' . $key . ' did not get the profile',
            );
        }
    }
}
