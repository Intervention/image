<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\FillModifier;

/**
 * @method SizeInterface cropSize(ImageInterface $image)
 * @property mixed $background
 */
class ResizeCanvasModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resize = $this->cropSize($image);
        $background = $this->driver()->handleInput($this->background);

        foreach ($image as $frame) {
            $this->modify($frame, $resize, $background);
        }

        return $image;
    }

    protected function modify(
        FrameInterface $frame,
        SizeInterface $resize,
        ColorInterface $background,
    ): void {
        // create new gd image
        $modified = $this->driver()->createImage(
            $resize->width(),
            $resize->height()
        )->modify(
            new FillModifier($background)
        )->core()->native();

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha(
            $modified,
            $background->channel(Red::class)->value(),
            $background->channel(Green::class)->value(),
            $background->channel(Blue::class)->value(),
            127,
        );

        imagealphablending($modified, false); // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $resize->pivot()->x() * -1,
            $resize->pivot()->y() * -1,
            $resize->pivot()->x() * -1 + $frame->size()->width() - 1,
            $resize->pivot()->y() * -1 + $frame->size()->height() - 1,
            $transparent
        );

        // copy image from original with blending alpha
        imagealphablending($modified, true);
        imagecopyresampled(
            $modified,
            $frame->native(),
            $resize->pivot()->x() * -1,
            $resize->pivot()->y() * -1,
            0,
            0,
            $frame->size()->width(),
            $frame->size()->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as recource
        $frame->setNative($modified);
    }
}
