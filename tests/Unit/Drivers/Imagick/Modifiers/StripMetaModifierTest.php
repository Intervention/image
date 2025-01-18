<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
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
        $result = $image->toJpeg();
        $this->assertEmpty(exif_read_data($result->toFilePointer())['IFD0.Artist'] ?? null);
    }
}
