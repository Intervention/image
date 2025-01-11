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
        $quality = $this->quality === 100 && defined('IMG_WEBP_LOSSLESS') ? IMG_WEBP_LOSSLESS : $this->quality;

        return $this->createEncodedImage(function ($pointer) use ($image, $quality): void {
            imagewebp($image->core()->native(), $pointer, $quality);
        }, 'image/webp');
    }
}
