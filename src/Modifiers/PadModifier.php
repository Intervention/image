<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class PadModifier extends ContainModifier
{
    /**
     * Calculate crop size of the pad resizing process
     *
     * @throws InvalidArgumentException
     */
    public function cropSize(ImageInterface $image): SizeInterface // TODO: make protected
    {
        return $image->size()
            ->containMax(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->resizeSize($image),
                $this->alignment
            );
    }
}
