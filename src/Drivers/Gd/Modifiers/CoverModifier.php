<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CoverModifier as GenericCoverModifier;

class CoverModifier extends GenericCoverModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $crop, $resize);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    protected function modifyFrame(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = Cloner::cloneEmpty($frame->native(), $resize);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            0,
            0,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $resize->width(),
            $resize->height(),
            $crop->width(),
            $crop->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
