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
        $gd = $this->maybeToPalette(clone $image)
            ->core()
            ->native();

        $data = $this->buffered(function () use ($gd) {
            imageinterlace($gd, $this->interlaced);
            imagepng($gd, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }

    /**
     * Maybe turn given image color to indexed palette version according to encoder settings
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

        if (is_null($this->indexed) && !$image->origin()->indexed()) {
            return $image;
        }

        return $image->reduceColors(255);
    }
}
