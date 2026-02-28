<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Config;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Modifiers\BlurModifier;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AbstractDriver::class)]
final class AbstractDriverTest extends BaseTestCase
{
    public function testConfig(): void
    {
        $config = new Config(autoOrientation: false);
        $driver = new GdDriver($config);
        $this->assertSame($config, $driver->config());
    }

    public function testConfigDefault(): void
    {
        $driver = new GdDriver();
        $this->assertInstanceOf(Config::class, $driver->config());
    }

    public function testHandleImageInputFailsWithEmptyDecoders(): void
    {
        $driver = new GdDriver();
        $this->expectException(StateException::class);
        $this->expectExceptionMessage('No decoders in input handler stack');
        $driver->handleImageInput('test', []);
    }

    public function testHandleImageInputFailsWithUnsupportedInput(): void
    {
        $driver = new GdDriver();
        $this->expectException(NotSupportedException::class);
        $this->expectExceptionMessage('Unsupported image source type');
        $driver->handleImageInput(12345);
    }

    public function testHandleColorInputFailsWithEmptyDecoders(): void
    {
        $driver = new GdDriver();
        $this->expectException(StateException::class);
        $this->expectExceptionMessage('No decoders in input handler stack');
        $driver->handleColorInput('test', []);
    }

    public function testHandleColorInputFailsWithUnsupportedInput(): void
    {
        $driver = new GdDriver();
        $this->expectException(NotSupportedException::class);
        $this->expectExceptionMessage('Unsupported color format');
        $driver->handleColorInput(new \stdClass());
    }

    public function testSpecializeModifier(): void
    {
        $driver = new GdDriver();
        $modifier = new BlurModifier(5);
        $result = $driver->specializeModifier($modifier);
        $this->assertInstanceOf(ModifierInterface::class, $result);
    }

    public function testSpecializeAnalyzer(): void
    {
        $driver = new GdDriver();
        $analyzer = new WidthAnalyzer();
        $result = $driver->specializeAnalyzer($analyzer);
        $this->assertNotNull($result);
    }

    public function testSpecializeEncoder(): void
    {
        $driver = new GdDriver();
        $encoder = new PngEncoder();
        $result = $driver->specializeEncoder($encoder);
        $this->assertNotNull($result);
    }

    public function testSpecializeDecoder(): void
    {
        $driver = new GdDriver();
        $decoder = new BinaryImageDecoder();
        $result = $driver->specializeDecoder($decoder);
        $this->assertNotNull($result);
    }

    public function testSpecializeModifierNotSupported(): void
    {
        $driver = new GdDriver();
        $modifier = new class () extends \Intervention\Image\Drivers\SpecializableModifier {
        };
        $this->expectException(NotSupportedException::class);
        $driver->specializeModifier($modifier);
    }

    public function testSpecializeNonSpecializableModifier(): void
    {
        $driver = new GdDriver();
        $modifier = new class () implements ModifierInterface {
            public function apply(ImageInterface $image): ImageInterface
            {
                return $image;
            }
        };
        $result = $driver->specializeModifier($modifier);
        $this->assertSame($modifier, $result);
    }

    public function testHandleColorInput(): void
    {
        $driver = new GdDriver();
        $result = $driver->handleColorInput('ff0000');
        $this->assertNotNull($result);
    }
}
