<?php

namespace Intervention\Image\Drivers\Gd\Traits;

use GdImage;

trait CanReduceColors
{
    /**
     * Reduce colors in a given GdImage to the given limit. Reduction is only
     * applied when the given limit is under the given threshold
     *
     * @param GdImage $gd
     * @param int $limit
     * @param int $threshold
     * @return GdImage
     */
    private function maybeReduceColors(GdImage $gd, int $limit, int $threshold = 256): GdImage
    {
        // no color limit: no reduction
        if ($limit === 0) {
            return $gd;
        }

        // limit is over threshold: no reduction
        if ($limit > $threshold) {
            return $gd;
        }

        // image size
        $width = imagesx($gd);
        $height = imagesy($gd);

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
        imagecopy($reduced, $gd, 0, 0, 0, 0, $width, $height);

        // reduce limit by one to include possible transparency in palette
        $limit = imagecolortransparent($gd) === -1 ? $limit : $limit - 1;

        // decrease colors
        imagetruecolortopalette($reduced, true, $limit);

        return $reduced;
    }
}
