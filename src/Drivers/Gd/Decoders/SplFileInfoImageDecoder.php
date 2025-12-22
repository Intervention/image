<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use SplFileInfo;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class SplFileInfoImageDecoder extends FilePathImageDecoder implements DecoderInterface
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
            throw new DecoderException('Failed to read path from ' . SplFileInfo::class);
        }

        return parent::decode($path);
    }
}
