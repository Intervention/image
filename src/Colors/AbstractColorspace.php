<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Interfaces\ColorspaceInterface;

abstract class AbstractColorspace implements ColorspaceInterface
{
    /**
     * Channel class names of colorspace.
     *
     * @var array<string>
     */
    protected static array $channels = [];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::channels()
     */
    public static function channels(): array
    {
        return static::$channels;
    }
}
