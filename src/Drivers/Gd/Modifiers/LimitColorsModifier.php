<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $limit
 * @property int $threshold
 */
class LimitColorsModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // no color limit: no reduction
        if ($this->limit === 0) {
            return $image;
        }

        // limit is over threshold: no reduction
        if ($this->limit > $this->threshold) {
            return $image;
        }

        $width = $image->width();
        $height = $image->height();

        foreach ($image as $frame) {
            // create empty gd
            $reduced = imagecreatetruecolor($width, $height);

            // create matte
            $matte = imagecolorallocatealpha($reduced, 255, 255, 255, 127);

            // fill with matte
            imagefill($reduced, 0, 0, $matte);

            imagealphablending($reduced, false);

            // set transparency and get transparency index
            imagecolortransparent($reduced, $matte);

            // copy original image
            imagecopy($reduced, $frame->native(), 0, 0, 0, 0, $width, $height);

            // reduce limit by one to include possible transparency in palette
            $limit = imagecolortransparent($frame->native()) === -1 ? $this->limit : $this->limit - 1;

            // decrease colors
            imagetruecolortopalette($reduced, true, $limit);

            $frame->setNative($reduced);
        }


        return $image;
    }
}
