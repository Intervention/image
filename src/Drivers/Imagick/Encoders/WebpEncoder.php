<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\WebpEncoder as GenericWebpEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class WebpEncoder extends GenericWebpEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws StateException
     * @throws EncoderException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'WEBP';
        $compression = Imagick::COMPRESSION_ZIP;

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        try {
            $imagick = $image->core()->native();
            $imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

            if (!$image->isAnimated()) {
                $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_MERGE);
            }

            $imagick->setFormat($format);
            $imagick->setImageFormat($format);
            $imagick->setCompression($compression);
            $imagick->setImageCompression($compression);
            $imagick->setImageCompressionQuality($this->quality);

            if ($this->quality === 100) {
                $imagick->setOption('webp:lossless', 'true');
            }

            return new EncodedImage($imagick->getImagesBlob(), 'image/webp');
        } catch (ImagickException $e) {
            throw new EncoderException('Failed to encode webp format', previous: $e);
        }
    }
}
