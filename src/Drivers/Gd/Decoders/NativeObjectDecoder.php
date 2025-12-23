<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use Exception;
use GdImage;
use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
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

        $result = imagesavealpha($input, true);
        if ($result === false) {
            throw new DriverException('Failed to convert image to true color');
        }

        // build image instance
        return new Image(
            $this->driver(),
            new Core([
                new Frame($input)
            ])
        );
    }

    /**
     * Decode image from given GIF source which can be either a file path or binary data
     *
     * Depending on the configuration, this is taken over by the native GD function
     * or, if animations are required, by our own extended decoder.
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

            $gif = GifDecoder::decode($input);
            $splitter = GifSplitter::create($gif)->split();
            $delays = $splitter->getDelays();

            // set loops on core
            if ($loops = $gif->getMainApplicationExtension()?->getLoops()) {
                $core->setLoops($loops);
            }

            // add GDImage instances to core
            foreach ($splitter->coalesceToResources() as $key => $native) {
                $core->push(
                    new Frame($native, $delays[$key] / 100)
                );
            }
        } catch (Exception $e) { // TODO: catch more detailed exception
            throw new ImageDecoderException('Failed to decode GIF format', previous: $e);
        }

        // create (possibly) animated image
        $image = new Image($this->driver(), $core);

        // set media type
        $image->origin()->setMediaType('image/gif');

        return $image;
    }
}
