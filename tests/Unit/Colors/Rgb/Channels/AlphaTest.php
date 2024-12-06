<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Channels;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Alpha::class)]
final class AlphaTest extends BaseTestCase
{
    public function testToString(): void
    {
        $alpha = new Alpha(255 / 3);
        $this->assertEquals('0.333333', $alpha->toString());
        $this->assertEquals('0.333333', (string) $alpha);
    }
}
