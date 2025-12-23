<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ImageInterface;

class ImageDecoder extends AbstractDecoder
{
    /**
     * Image decoders
     */
    public const DECODERS = [
        NativeObjectDecoder::class,
        FilePointerImageDecoder::class,
        EncodedImageObjectDecoder::class,
        Base64ImageDecoder::class,
        DataUriImageDecoder::class,
        BinaryImageDecoder::class,
        FilePathImageDecoder::class,
        SplFileInfoImageDecoder::class,
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
