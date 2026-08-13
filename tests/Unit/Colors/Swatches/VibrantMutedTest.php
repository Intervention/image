<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Swatches;

use Intervention\Image\Colors\Swatches\VibrantMuted;
use Intervention\Image\Interfaces\ColorFilterInterface;
use Intervention\Image\Tests\BaseTestCase;

class VibrantMutedTest extends BaseTestCase
{
    public function testColorFilter(): void
    {
        $swatches = new VibrantMuted();
        $this->assertInstanceOf(ColorFilterInterface::class, $swatches->colorFilter());
    }
}
