<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface cropSize(ImageInterface $image)
 * @property mixed $background
 */
class ResizeCanvasModifier extends DriverSpecialized implements ModifierInterface
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

    /**
     * @throws ColorException
     */
    protected function modify(
        FrameInterface $frame,
        SizeInterface $resize,
        ColorInterface $background,
    ): void {
        // create new canvas with target size & target background color
        $modified = Cloner::cloneEmpty($frame->native(), $resize, $background);

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
        // imagecolortransparent($modified, $transparent);
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
