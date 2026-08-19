<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Themes\VibrantMuted;

use Intervention\Image\Color;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Colors\Themes\VibrantMuted\Definition;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class DefinitionTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testExtractColors(DriverInterface $driver): void
    {
        $definition = new Definition();
        $result = $definition->extractColors(Resource::create('trim.png')->imageObject($driver));
        $this->assertInstanceOf(PaletteInterface::class, $result);
        $this->assertCount(16, $result);
    }

    public function testThemeColors(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0), // vibrant
            Color::rgb(170, 100, 100), // muted
            Color::rgb(100, 0, 0), // dark vibrant
            Color::rgb(80, 45, 45), // dark muted
            Color::rgb(255, 200, 200), // light vibrant
            Color::rgb(180, 170, 160), // light muted
        ]);

        $definition = new Definition();
        $result = $definition->themeColors($palette);

        $this->assertColor(255, 0, 0, 255, $result->vibrant);
        $this->assertColor(170, 100, 100, 255, $result->muted);
        $this->assertColor(100, 0, 0, 255, $result->darkVibrant);
        $this->assertColor(80, 45, 45, 255, $result->darkMuted);
        $this->assertColor(255, 200, 200, 255, $result->lightVibrant);
        $this->assertColor(180, 170, 160, 255, $result->lightMuted);
    }

    public function testThemeColorsNotFound(): void
    {
        $palette = new Palette([
            Color::rgb(255, 0, 0), // vibrant
            Color::rgb(170, 100, 100), // muted
            Color::rgb(100, 0, 0), // dark vibrant
            Color::rgb(80, 45, 45), // dark muted
            Color::rgb(0, 0, 0),
            Color::rgb(0, 1, 0),
        ]);

        $definition = new Definition();
        $result = $definition->themeColors($palette);

        $this->assertColor(255, 0, 0, 255, $result->vibrant);
        $this->assertColor(170, 100, 100, 255, $result->muted);
        $this->assertColor(100, 0, 0, 255, $result->darkVibrant);
        $this->assertColor(80, 45, 45, 255, $result->darkMuted);
        $this->assertNull($result->lightVibrant);
        $this->assertNull($result->lightMuted);
    }
}
