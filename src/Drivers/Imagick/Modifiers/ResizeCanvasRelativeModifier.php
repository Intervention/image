<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasRelativeModifier extends ResizeCanvasModifier
{
    protected function cropSize(ImageInterface $image, bool $relative = false): SizeInterface
    {
        return parent::cropSize($image, true);
    }
}
