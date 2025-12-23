<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\ImageDecoderException;
use SplFileInfo;
use Intervention\Image\Interfaces\ImageInterface;

class SplFileInfoImageDecoder extends FilePathImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $input instanceof SplFileInfo;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface
    {
        $path = $input->getRealPath();

        if ($path === false) {
            throw new FileNotReadableException('Failed to read path from ' . SplFileInfo::class);
        }

        try {
            return parent::decode($path);
        } catch (DecoderException) {
            throw new ImageDecoderException(SplFileInfo::class . ' contains unsupported image type');
        }
    }
}
