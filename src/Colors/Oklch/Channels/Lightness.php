<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch\Channels;

use Intervention\Image\Colors\FloatColorChannel;

class Lightness extends FloatColorChannel
{
    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::min()
     */
    public static function min(): float
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::max()
     */
    public static function max(): float
    {
        return 1;
    }
}
