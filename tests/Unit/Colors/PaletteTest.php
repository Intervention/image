<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Colors\Palette;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(Palette::class)]
class PaletteTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testCountableIteration(DriverInterface $driver): void
    {
        $image = Resource::create('red.gif')->imageObject($driver);
        $palette = new Palette($image);

        $this->assertCount(1, $palette);

        foreach ($palette->colors() as $color) {
            $this->assertInstanceOf(ColorInterface::class, $color);
        }

        foreach ($palette as $color) {
            $this->assertInstanceOf(ColorInterface::class, $color);
        }
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testFirstLast(DriverInterface $driver): void
    {
        $image = Resource::create('red.gif')->imageObject($driver);
        $palette = new Palette($image);
        $this->assertInstanceOf(ColorInterface::class, $palette->first());
        $this->assertInstanceOf(ColorInterface::class, $palette->last());
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDominantExact(DriverInterface $driver): void
    {
        $image = Resource::create('gradient.gif')->imageObject($driver);
        $palette = (new Palette($image))->dominant(6);
        $this->assertCount(6, $palette);
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testDominantLess(DriverInterface $driver): void
    {
        $image = Resource::create('trim.png')->imageObject($driver);
        $palette = (new Palette($image))->dominant(6);
        $this->assertCount(2, $palette);
    }
}
