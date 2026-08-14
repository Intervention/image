<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;

trait CanHashColor
{
    /**
     * Build hash for color.
     */
    protected function hashColor(ColorInterface $color): string
    {
        $channelValues = array_map(
            fn(ColorChannelInterface $channel): int|float => $channel->value(),
            $color->channels(),
        );

        return md5($color->colorspace()::class . implode(',', $channelValues));
    }
}
