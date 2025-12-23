<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Color;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class ColorTest extends BaseTestCase
{
    #[DataProviderExternal(ColorDataProvider::class, 'rgbArray')]
    #[DataProviderExternal(ColorDataProvider::class, 'rgbString')]
    public function testRgb(mixed $input, array $channels): void
    {
        $color = Color::rgb(...$input);
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int => $channel->value(), $color->channels()),
        );
    }
}
