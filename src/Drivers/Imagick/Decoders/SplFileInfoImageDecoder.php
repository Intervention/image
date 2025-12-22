<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use SplFileInfo;
use Intervention\Image\Interfaces\ColorInterface;
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
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        return parent::decode($input->getRealPath());
    }
}
