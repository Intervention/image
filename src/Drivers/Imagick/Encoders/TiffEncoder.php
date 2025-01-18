<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\TiffEncoder as GenericTiffEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class TiffEncoder extends GenericTiffEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'TIFF';

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($imagick->getImageCompression());
        $imagick->setImageCompression($imagick->getImageCompression());
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/tiff');
    }
}
