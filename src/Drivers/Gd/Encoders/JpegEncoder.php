<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        $output = Cloner::cloneBlended(
            $image->core()->native(),
            background: $blendingColor
        );

        $data = $this->buffered(function () use ($output) {
            imageinterlace($output, $this->progressive);
            imagejpeg($output, null, $this->quality);
        });

        return new EncodedImage($data, 'image/jpeg');
    }
}
