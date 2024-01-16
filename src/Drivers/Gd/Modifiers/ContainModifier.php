<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface getCropSize(ImageInterface $image)
 * @method SizeInterface getResizeSize(ImageInterface $image)
 * @property int $width
 * @property int $height
 * @property mixed $background
 * @property string $position
 */
class ContainModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $background = $this->driver()->handleInput($this->background);
        $blendingColor = $image->blendingColor();

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $background, $blendingColor);
        }

        return $image;
    }

    protected function modify(
        FrameInterface $frame,
        SizeInterface $crop,
        SizeInterface $resize,
        ColorInterface $background,
        ColorInterface $blendingColor
    ): void {
        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $resize, $background);

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha(
            $modified,
            $blendingColor->channel(Red::class)->value(),
            $blendingColor->channel(Green::class)->value(),
            $blendingColor->channel(Blue::class)->value(),
            127,
        );
        imagealphablending($modified, false); // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $crop->pivot()->x() + $crop->width() - 1,
            $crop->pivot()->y() + $crop->height() - 1,
            $transparent
        );

        // copy image from original with blending alpha
        imagealphablending($modified, true);
        imagecopyresampled(
            $modified,
            $frame->native(),
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            0,
            0,
            $crop->width(),
            $crop->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as recource
        $frame->setNative($modified);
    }
}
