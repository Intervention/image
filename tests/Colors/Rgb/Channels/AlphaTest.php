<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Rgb\Channels;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Tests\TestCase;

#[CoversClass(\Intervention\Image\Colors\Rgb\Channels\Alpha::class)]
final class AlphaTest extends TestCase
{
    public function testToString(): void
    {
        $alpha = new Alpha(255 / 3);
        $this->assertEquals('0.333333', $alpha->toString());
        $this->assertEquals('0.333333', (string) $alpha);
    }
}
