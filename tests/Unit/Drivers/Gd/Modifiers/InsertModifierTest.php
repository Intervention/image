<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\InsertModifier;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Drivers\Gd\Modifiers\InsertModifier as InsertModifierGd;
use Intervention\Image\Tests\Resource;

#[RequiresPhpExtension('gd')]
#[CoversClass(InsertModifier::class)]
#[CoversClass(InsertModifierGd::class)]
final class InsertModifierTest extends GdTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->colorAt(300, 25)->toHex());
        $image->modify(new InsertModifier(Resource::create('circle.png')->path(), 0, 0, Alignment::TOP_RIGHT));
        $this->assertEquals('32250d', $image->colorAt(300, 25)->toHex());
    }

    public function testColorChangeTransparencyPng(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->colorAt(300, 25)->toHex());
        $image->modify(new InsertModifier(Resource::create('circle.png')->path(), 0, 0, Alignment::TOP_RIGHT, .5));
        $this->assertColor(152, 112, 40, 255, $image->colorAt(300, 25), tolerance: 1);
        $this->assertColor(255, 202, 107, 255, $image->colorAt(274, 5), tolerance: 1);
    }

    public function testColorChangeTransparencyJpeg(): void
    {
        $image = $this->createTestImage(16, 16)->fill('0000ff');
        $this->assertEquals('0000ff', $image->colorAt(10, 10)->toHex());
        $image->modify(new InsertModifier(Resource::create('exif.jpg')->path(), transparency: .5));
        $this->assertColor(127, 83, 127, 255, $image->colorAt(10, 10), tolerance: 1);
    }

    public function testInsertWithTransparencyKeepsTransparentBaseTransparent(): void
    {
        $image = $this->createTransparentBase(50, 50);
        $this->assertTransparency($image->colorAt(0, 0));

        $image->modify(new InsertModifier(
            Resource::create('circle.png')->path(),
            0,
            0,
            Alignment::TOP_LEFT,
            .5,
        ));

        // circle.png's (0, 0) is fully transparent. The previous
        // imagecreatetruecolor + imagecopymerge path filled the whole
        // watermark bbox with opaque black wherever the base was
        // transparent. The corner must still be transparent.
        $this->assertTransparency($image->colorAt(0, 0));
    }

    private function createTransparentBase(int $width, int $height): Image
    {
        $gd = imagecreatetruecolor($width, $height);
        imagealphablending($gd, false);
        imagesavealpha($gd, true);
        $transparent = imagecolorallocatealpha($gd, 0, 0, 0, 127);
        imagefilledrectangle($gd, 0, 0, $width - 1, $height - 1, $transparent);

        return new Image(
            new Driver(),
            new Core([
                new Frame($gd),
            ]),
        );
    }
}
