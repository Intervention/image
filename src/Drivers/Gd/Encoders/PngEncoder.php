<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $image = $this->maybeToPalette(clone $image);

        $gd = $image->core()->native();

        $data = $this->buffered(function () use ($gd) {
            imageinterlace($gd, $this->interlaced);
            imagesavealpha($gd, true);
            imagepng($gd, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }

    /**
     * Transform given image to indexed palette version according to encoder settings
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @return ImageInterface
     */
    private function maybeToPalette(ImageInterface $image): ImageInterface
    {
        if ($this->indexed === false) {
            return $image;
        }

        if (is_null($this->indexed) && !$image->origin()->isIndexed()) {
            return $image;
        }

        return $image->reduceColors(256);
    }
}
