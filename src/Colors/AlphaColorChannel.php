<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

abstract class AlphaColorChannel extends FloatColorChannel
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
