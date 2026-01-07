<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class PadModifier extends ContainModifier
{
    /**
     * Calculate crop size of the pad resizing process
     */
    protected function cropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->pad(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->resizeSize($image),
                $this->alignment
            );
    }
}
