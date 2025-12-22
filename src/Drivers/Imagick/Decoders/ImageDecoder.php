<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class ImageDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * Image decoders
     */
    public const DECODERS = [
        NativeObjectDecoder::class,
        FilePointerImageDecoder::class,
        Base64ImageDecoder::class,
        FilePathImageDecoder::class,
        SplFileInfoImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        EncodedImageObjectDecoder::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        foreach (self::DECODERS as $classname) {
            if (new $classname()->supports($input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface
    {
        return InputHandler::withDecoders(
            self::DECODERS,
            driver: $this->driver(),
        )->handle($input);
    }
}
