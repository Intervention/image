<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Generator;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(RemoveAnimationModifier::class)]
final class RemoveAnimationModifierTest extends BaseTestCase
{
    #[DataProvider('normalizePositionProvider')]
    public function testNormalizePosition(int|string $position, int $frames, int $normalized): void
    {
        $modifier = new class ($position) extends RemoveAnimationModifier
        {
            public function testResult(int $frames): int
            {
                $image = Mockery::mock(ImageInterface::class)->makePartial();
                $image->shouldReceive('count')->andReturn($frames);

                return $this->normalizePosition($image);
            }
        };

        $this->assertEquals($normalized, $modifier->testResult($frames));
    }

    public static function normalizePositionProvider(): Generator
    {
        yield [0, 100, 0];
        yield [10, 100, 10];
        yield ['10', 100, 10];
        yield ['0%', 100, 0];
        yield ['50%', 100, 50];
        yield ['100%', 100, 99];
    }
}
