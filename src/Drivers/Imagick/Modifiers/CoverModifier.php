<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CoverModifier as GenericCoverModifier;

class CoverModifier extends GenericCoverModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $frame->native()->cropImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x(),
                $crop->pivot()->y()
            );

            $frame->native()->scaleImage(
                $resize->width(),
                $resize->height()
            );

            $frame->native()->setImagePage(0, 0, 0, 0);
        }

        return $image;
    }
}
