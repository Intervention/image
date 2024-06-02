<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;

class FilePathImageDecoder extends NativeObjectDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!$this->isFile($input)) {
            throw new DecoderException('Unable to decode input');
        }

        // detect media (mime) type
        $mediaType = $this->getMediaTypeByFilePath($input);

        $image = match ($mediaType) {
            // gif files might be animated and therefore cannot
            // be handled by the standard GD decoder.
            'image/gif' => $this->decodeGif($input),
            default => parent::decode(match ($mediaType) {
                'image/jpeg', 'image/jpg', 'image/pjpeg' => @imagecreatefromjpeg($input),
                'image/webp', 'image/x-webp' => @imagecreatefromwebp($input),
                'image/png', 'image/x-png' => @imagecreatefrompng($input),
                'image/avif', 'image/x-avif' => @imagecreatefromavif($input),
                'image/bmp',
                'image/ms-bmp',
                'image/x-bitmap',
                'image/x-bmp',
                'image/x-ms-bmp',
                'image/x-win-bitmap',
                'image/x-windows-bmp',
                'image/x-xbitmap' => @imagecreatefrombmp($input),
                default => throw new DecoderException('Unable to decode input'),
            }),
        };

        // set file path & mediaType on origin
        $image->origin()->setFilePath($input);
        $image->origin()->setMediaType($mediaType);

        // extract exif
        $image->setExif($this->extractExifData($input));

        // adjust image orientation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        return $image;
    }
}
