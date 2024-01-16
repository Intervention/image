<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasRelativeModifier extends ResizeCanvasModifier
{
    public function cropSize(ImageInterface $image): SizeInterface
    {
        $width = is_null($this->width) ? $image->width() : $image->width() + $this->width;
        $height = is_null($this->height) ? $image->height() : $image->height() + $this->height;

        return (new Rectangle($width, $height))
            ->alignPivotTo(
                $image->size(),
                $this->position
            );
    }
}
