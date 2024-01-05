<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\SpecializedModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property null|int $width
 * @property null|int $height
 */
class ResizeModifier extends SpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo =  $this->getAdjustedSize($image);
        foreach ($image as $frame) {
            $this->resizeFrame($frame, $resizeTo);
        }

        return $image;
    }

    private function resizeFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $resizeTo->width(),
            $resizeTo->height()
        );

        // get current GDImage
        $current = $frame->native();

        // retain resolution
        $this->copyResolution($current, $modified);

        // preserve transparency
        $transIndex = imagecolortransparent($current);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        } else {
            $transColor = imagecolorallocatealpha($modified, 255, 255, 255, 127);
            imagealphablending($modified, true);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);

            imagealphablending($modified, true);
            imagesavealpha($modified, true);
        }

        // copy content from resource
        imagecopyresampled(
            $modified,
            $current,
            $resizeTo->pivot()->x(),
            $resizeTo->pivot()->y(),
            0,
            0,
            $resizeTo->width(),
            $resizeTo->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );


        // set new content as recource
        $frame->setNative($modified);
    }

    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
