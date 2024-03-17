<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeModifier as GenericResizeModifier;

class ResizeModifier extends GenericResizeModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);

        foreach ($image as $frame) {
            $frame->native()->scaleImage(
                $resizeTo->width(),
                $resizeTo->height()
            );
        }

        return $image;
    }

    /**
     * @throws RuntimeException
     */
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
