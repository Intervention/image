<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Alignment;
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
        $image->modify(new InsertModifier(Resource::create('circle.png')->path(), Alignment::TOP_RIGHT, 0, 0));
        $this->assertEquals('32250d', $image->colorAt(300, 25)->toHex());
    }

    public function testColorChangeOpacityPng(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->colorAt(300, 25)->toHex());
        $image->modify(new InsertModifier(Resource::create('circle.png')->path(), Alignment::TOP_RIGHT, 0, 0, 50));
        $this->assertColor(152, 112, 40, 1, $image->colorAt(300, 25), tolerance: 1);
        $this->assertColor(255, 202, 107, 1, $image->colorAt(274, 5), tolerance: 1);
    }

    public function testColorChangeOpacityJpeg(): void
    {
        $image = $this->createTestImage(16, 16)->fill('0000ff');
        $this->assertEquals('0000ff', $image->colorAt(10, 10)->toHex());
        $image->modify(new InsertModifier(Resource::create('exif.jpg')->path(), opacity: 50));
        $this->assertColor(127, 83, 127, 1, $image->colorAt(10, 10), tolerance: 1);
    }
}
