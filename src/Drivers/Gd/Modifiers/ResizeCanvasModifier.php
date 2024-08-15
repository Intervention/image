<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeCanvasModifier as GenericResizeCanvasModifier;

class ResizeCanvasModifier extends GenericResizeCanvasModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
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
        // create new canvas with target size & transparent background color
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

        // create transparent area to place the original on top
        imagealphablending($modified, false); // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $resize->pivot()->x() * -1,
            $resize->pivot()->y() * -1,
            abs($resize->pivot()->x()) + $frame->size()->width() - 1,
            abs($resize->pivot()->y()) + $frame->size()->height() - 1,
            $transparent,
        );

        // place original
        imagecopy(
            $modified,
            $frame->native(),
            $resize->pivot()->x() * -1,
            $resize->pivot()->y() * -1,
            0,
            0,
            $frame->size()->width(),
            $frame->size()->height(),
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
