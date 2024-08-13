<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeModifier as GenericResizeModifier;

class ResizeModifier extends GenericResizeModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);
        foreach ($image as $frame) {
            $this->resizeFrame($frame, $resizeTo);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    private function resizeFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create empty canvas in target size
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            $resizeTo->pivot()->x(),
            $resizeTo->pivot()->y(),
            0,
            0,
            $resizeTo->width(),
            $resizeTo->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }

    /**
     * Return the size the modifier will resize to
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @throws GeometryException
     * @return SizeInterface
     */
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
