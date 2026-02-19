<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ScaleDownModifier extends ResizeModifier
{
    protected function adjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->scaleDown($this->width, $this->height);
    }
}
