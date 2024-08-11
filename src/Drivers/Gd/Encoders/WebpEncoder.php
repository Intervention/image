<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\WebpEncoder as GenericWebpEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class WebpEncoder extends GenericWebpEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $quality = $this->quality === 100 ? IMG_WEBP_LOSSLESS : $this->quality;
        $data = $this->buffered(function () use ($image, $quality) {
            imagewebp($image->core()->native(), null, $quality);
        });

        return new EncodedImage($data, 'image/webp');
    }
}
