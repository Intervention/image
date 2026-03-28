<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ContainDownModifier extends ContainModifier
{
    /**
     * Calculate crop size of the contain down resizing process.
     *
     * @throws InvalidArgumentException
     */
    protected function cropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->containDown(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->resizeSize($image),
                $this->alignment
            );
    }
}
