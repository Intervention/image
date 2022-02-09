<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Abstract\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\TypeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier
 */
final class AbstractRotateModifierTest extends TestCase
{
    public function providerRotationAngle(): iterable
    {
        yield '0 degrees' => [0.0, 0];
        yield '90 degrees' => [90.0, 90];
        yield '180 degrees' => [180.0, 180];
        yield '270 degrees' => [270.0, 270];
        yield '360 degrees' => [0.0, 360];
    }

    /** @dataProvider providerRotationAngle */
    public function testRotationAngle(float $expected, int $angle): void
    {
        $modifier = $this->getModifier($angle, 'abcdef');

        static::assertSame($expected, $modifier->rotationAngle());
    }

    public function testBackgroundColor(): void
    {
        $modifier = $this->getModifier(90, 'abcdef');
        $color = $modifier->backgroundColor();

        static::assertSame(255, $color->red());
    }

    public function testBackgroundColorInvalidValueThrowsException(): void
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage('Argument #2 must be a color value');

        $modifier = $this->getModifier(90, 'bad value');
        $modifier->backgroundColor();
    }

    private function getModifier(float $angle, $background): AbstractRotateModifier
    {
        return new class ($angle, $background) extends AbstractRotateModifier {
            public function rotationAngle(): float
            {
                return parent::rotationAngle();
            }

            public function backgroundColor(): ColorInterface
            {
                return parent::backgroundColor();
            }

            public function handleInput($input): ImageInterface|ColorInterface
            {
                if ($this->background === 'bad value') {
                    throw new DecoderException();
                }

                $color = Mockery::mock(ColorInterface::class);
                $color->shouldReceive('red')->andReturn(255);

                return $color;
            }
        };
    }
}
