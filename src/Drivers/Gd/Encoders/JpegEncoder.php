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
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = Cloner::cloneBlended($image->core()->native(), background: $image->blendingColor());

        $data = $this->buffered(function () use ($output) {
            imageinterlace($output, $this->progressive);
            imagejpeg($output, null, $this->quality);
        });

        return new EncodedImage($data, 'image/jpeg');
    }
}
