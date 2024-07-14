<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use GdImage;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = $this->maybeToPalette(clone $image); // use clone because colors may be reduced

        $data = $this->buffered(function () use ($output) {
            imageinterlace($output, $this->interlaced);
            imagesavealpha($output, true);
            imagepng($output, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }

    /**
     * Transform given image to indexed palette version according to encoder settings
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @return GdImage
     */
    private function maybeToPalette(ImageInterface $image): GdImage
    {
        if ($this->indexed === false) {
            return $image->core()->native();
        }

        return $image->reduceColors(256)->core()->native();
    }
}
