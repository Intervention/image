<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Channels;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(Alpha::class)]
final class AlphaTest extends BaseTestCase
{
    public function testToString(): void
    {
        $alpha = new Alpha(.333333);
        $this->assertEquals('0.33', $alpha->toString());
        $this->assertEquals('0.33', (string) $alpha);
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(float $value, int $percent, int $result): void
    {
        $this->assertEquals($result, (new Alpha($value))->scale($percent)->value());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [0, 0, 0];
        yield [1, 0, 255];
        yield [.5, 0, 128];

        yield [0, 50, 128];
        yield [1, 50, 255];
        yield [.5, 50, 192];

        yield [.5, 100, 255];
        yield [0, 100, 255];
        yield [1, 100, 255];

        yield [0, -50, 0];
        yield [1, -50, 127];
        yield [.5, -50, 64];

        yield [.5, -100, 0];
        yield [0, -100, 0];
        yield [1, -100, 0];
    }
}
