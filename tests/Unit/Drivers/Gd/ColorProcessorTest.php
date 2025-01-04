<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\ColorProcessor;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(ColorProcessor::class)]
final class ColorProcessorTest extends BaseTestCase
{
    public function testColorToNative(): void
    {
        $processor = new ColorProcessor();
        $result = $processor->colorToNative(new Color(255, 55, 0, 255));
        $this->assertEquals(16725760, $result);
    }

    public function testNativeToColorInteger(): void
    {
        $processor = new ColorProcessor();
        $result = $processor->nativeToColor(16725760);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(55, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
        $this->assertEquals(255, $result->channel(Alpha::class)->value());
    }

    public function testNativeToColorArray(): void
    {
        $processor = new ColorProcessor();
        $result = $processor->nativeToColor(['red' => 255, 'green' => 55, 'blue' => 0, 'alpha' => 0]);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(55, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
        $this->assertEquals(255, $result->channel(Alpha::class)->value());
    }

    public function testNativeToColorInvalid(): void
    {
        $processor = new ColorProcessor();
        $this->expectException(ColorException::class);
        $processor->nativeToColor('test');
    }
}
