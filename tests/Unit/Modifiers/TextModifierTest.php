<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TextModifier::class)]
final class TextModifierTest extends BaseTestCase
{
    public function testStrokeOffsets(): void
    {
        $modifier = new class ('test', new Point(), new Font()) extends TextModifier
        {
            /**
             * @return array<?Point>
             */
            public function testStrokeOffsets(FontInterface $font): array
            {
                return $this->strokeOffsets($font);
            }
        };

        $this->assertEquals([], $modifier->testStrokeOffsets(new Font()));

        $this->assertEquals([
            new Point(-1, -1),
            new Point(-1, 0),
            new Point(-1, 1),
            new Point(0, -1),
            new Point(0, 0),
            new Point(0, 1),
            new Point(1, -1),
            new Point(1, 0),
            new Point(1, 1),
        ], $modifier->testStrokeOffsets((new Font())->setStrokeWidth(1)));
    }

    public function testTextColor(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(false);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->with('ff0000')->andReturn($color);

        $modifier = $this->createTextModifierWithDriver($driver);
        $modifier->font = (new Font())->setColor('ff0000');

        $result = $modifier->getTextColor();
        $this->assertSame($color, $result);
    }

    public function testTextColorWithStrokeAndTransparency(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(true);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->with('ff0000')->andReturn($color);

        $modifier = $this->createTextModifierWithDriver($driver);
        $modifier->font = (new Font())->setColor('ff0000')->setStrokeWidth(2);

        $this->expectException(StateException::class);
        $modifier->getTextColor();
    }

    public function testTextColorWithStrokeAndOpaque(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(false);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->with('ff0000')->andReturn($color);

        $modifier = $this->createTextModifierWithDriver($driver);
        $modifier->font = (new Font())->setColor('ff0000')->setStrokeWidth(2);

        $result = $modifier->getTextColor();
        $this->assertSame($color, $result);
    }

    public function testStrokeColor(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(false);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->with('ffffff')->andReturn($color);

        $modifier = $this->createTextModifierWithDriver($driver);
        $modifier->font = new Font();

        $result = $modifier->getStrokeColor();
        $this->assertSame($color, $result);
    }

    public function testStrokeColorTransparent(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(true);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->with('ffffff')->andReturn($color);

        $modifier = $this->createTextModifierWithDriver($driver);
        $modifier->font = new Font();

        $this->expectException(StateException::class);
        $modifier->getStrokeColor();
    }

    /**
     * @param DriverInterface $driver
     */
    private function createTextModifierWithDriver(mixed $driver): TextModifier
    {
        return new class ('test', new Point(), new Font(), $driver) extends TextModifier
        {
            public function __construct(
                string $text,
                Point $position,
                FontInterface $font,
                private readonly DriverInterface $mockDriver,
            ) {
                parent::__construct($text, $position, $font);
                $this->driver = $this->mockDriver;
            }

            public function getTextColor(): ColorInterface
            {
                return $this->textColor();
            }

            public function getStrokeColor(): ColorInterface
            {
                return $this->strokeColor();
            }
        };
    }
}
