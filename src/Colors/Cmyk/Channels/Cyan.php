<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Cmyk\Channels;

use Intervention\Image\Colors\IntegerColorChannel;

class Cyan extends IntegerColorChannel
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
        return 100;
    }
}
