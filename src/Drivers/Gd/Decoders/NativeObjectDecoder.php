<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use GdImage;
use Intervention\Gif\Exceptions\GifException;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;

class NativeObjectDecoder extends AbstractDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $input instanceof GdImage;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     * @throws StateException
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!($input instanceof GdImage)) {
            throw new InvalidArgumentException('Input must be of type ' . GdImage::class);
        }

        if (!imageistruecolor($input)) {
            $result = imagepalettetotruecolor($input);
            if ($result === false) {
                throw new DriverException('Failed to convert image to true color');
            }
        }

        imagesavealpha($input, true);

        // build image instance
        return new Image(
            $this->driver(),
            new Core([
                new Frame($input)
            ])
        );
    }

    /**
     * Decode image from given GIF source which can be either a file path or binary data.
     *
     * Depending on the configuration, this is taken over by the native GD function
     * or, if animations are required, by our own extended decoder.
     *
     * @throws InvalidArgumentException
     * @throws ImageDecoderException
     * @throws DriverException
     * @throws StateException
     */
    protected function decodeGif(mixed $input): ImageInterface
    {
        // create non-animated image depending on config
        if ($this->driver()->config()->decodeAnimation === false) {
            $native = $this->isGifFormat($input) ? @imagecreatefromstring($input) : @imagecreatefromgif($input);

            if ($native === false) {
                throw new ImageDecoderException('Failed to decode GIF format');
            }

            $image = self::decode($native);
            $image->origin()->setMediaType('image/gif');

            return $image;
        }

        try {
            // create empty core
            $core = new Core();

            // add frames to core
            $splitter = GifSplitter::decode($input)
                ->split()
                ->flatten()
                ->each(function (GdImage $native, int $delay) use ($core): void {
                    $core->push(new Frame($native, $delay / 100));
                });

            // set loops on core
            $core->setLoops($splitter->loops());
        } catch (GifException $e) {
            throw new ImageDecoderException('Failed to decode GIF format', previous: $e);
        }

        // create (possibly) animated image
        $image = new Image($this->driver(), $core);

        // set media type
        $image->origin()->setMediaType('image/gif');

        return $image;
    }
}
