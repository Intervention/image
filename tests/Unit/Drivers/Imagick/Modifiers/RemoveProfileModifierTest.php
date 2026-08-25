<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Colors\Profile;
use Intervention\Image\Drivers\Imagick\Modifiers\RemoveProfileModifier as ImagickRemoveProfileModifier;
use Intervention\Image\Modifiers\RemoveProfileModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(RemoveProfileModifier::class)]
#[CoversClass(ImagickRemoveProfileModifier::class)]
final class RemoveProfileModifierTest extends ImagickTestCase
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
        $image = $this->readTestImage('test.jpg');
        $this->assertNotEmpty($image->core()->native()->getImageProfiles('icc'));

        $image->modify(new RemoveProfileModifier());

        $this->assertEmpty($image->core()->native()->getImageProfiles('icc'));
    }

    public function testApplyAnimated(): void
    {
        $image = $this->createTestAnimation();

        // set the profile on every frame through the native api, going
        // through ProfileModifier would only cover the frames that one
        // reaches and could hide a partial removal here
        $profile = (string) $this->getTestProfile();
        foreach ($image as $frame) {
            $frame->native()->profileImage('icc', $profile);
        }

        $image->modify(new RemoveProfileModifier());

        foreach ($image as $key => $frame) {
            $this->assertEmpty(
                $frame->native()->getImageProfiles('icc'),
                'Frame ' . $key . ' kept its profile',
            );
        }
    }
}
