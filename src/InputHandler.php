<?php

declare(strict_types=1);

namespace Intervention\Image;

use Generator;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder as CmykStringColorDecoder;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder as HslStringColorDecoder;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder as HsvStringColorDecoder;
use Intervention\Image\Colors\Oklab\Decoders\StringColorDecoder as OklabStringColorDecoder;
use Intervention\Image\Colors\Oklch\Decoders\StringColorDecoder as OklchStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\NamedColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\ColorObjectDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Decoders\EncodedImageObjectDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Decoders\FilePointerImageDecoder;
use Intervention\Image\Decoders\ImageObjectDecoder;
use Intervention\Image\Decoders\NativeObjectDecoder;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\InputHandlerInterface;

class InputHandler implements InputHandlerInterface
{
    /**
     * All available image decoders.
     */
    public const array IMAGE_DECODERS = [
        ImageObjectDecoder::class,
        NativeObjectDecoder::class,
        FilePointerImageDecoder::class,
        SplFileInfoImageDecoder::class,
        EncodedImageObjectDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
        BinaryImageDecoder::class,
        FilePathImageDecoder::class,
    ];

    /**
     * All available color decoders.
     */
    public const array COLOR_DECODERS = [
        NamedColorDecoder::class,
        TransparentColorDecoder::class,
        ColorObjectDecoder::class,
        RgbHexColorDecoder::class,
        RgbStringColorDecoder::class,
        CmykStringColorDecoder::class,
        HsvStringColorDecoder::class,
        HslStringColorDecoder::class,
        OklabStringColorDecoder::class,
        OklchStringColorDecoder::class,
    ];

    /**
     * Create new input handler instance with given decoder classnames.
     *
     * @param array<string|DecoderInterface> $decoders
     * @return void
     */
    public function __construct(
        protected array $decoders = [],
        protected ?DriverInterface $driver = null,
    ) {
        //
    }

    /**
     * Static factory method to create input handler for both image and color handling.
     *
     * @param array<string|DecoderInterface> $decoders
     */
    public static function usingDecoders(array $decoders, ?DriverInterface $driver = null): self
    {
        return new self($decoders, $driver);
    }

    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     *
     * @throws InvalidArgumentException
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function handle(mixed $input): ImageInterface|ColorInterface
    {
        if ($input === null) {
            throw new InvalidArgumentException('Unable to decode null');
        }

        if ($input === '') {
            throw new InvalidArgumentException('Unable to decode empty string');
        }

        // if handler has only one single decoder run it can run directly
        if (count($this->decoders) === 1) {
            return $this->decoders()->current()->decode($input);
        }

        // multiple decoders: try to find the matching decoder for the input
        foreach ($this->decoders() as $decoder) {
            if ($decoder->supports($input)) {
                return $decoder->decode($input);
            }
        }

        throw new NotSupportedException('Unprocessable input');
    }

    /**
     * Yield all decoders.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    private function decoders(): Generator
    {
        foreach ($this->decoders as $decoder) {
            yield $this->decoder($decoder);
        }
    }

    /**
     * Resolve the given classname to an decoder object.
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     */
    private function decoder(string|DecoderInterface $decoder): DecoderInterface
    {
        if (($decoder instanceof DecoderInterface) && empty($this->driver)) {
            return $decoder;
        }

        if (($decoder instanceof DecoderInterface) && !empty($this->driver)) {
            return $this->driver->specializeDecoder($decoder);
        }

        if (!is_subclass_of($decoder, DecoderInterface::class)) {
            throw new InvalidArgumentException('Decoder must implement ' . DecoderInterface::class);
        }

        $resolved = new $decoder();

        if (empty($this->driver)) {
            return $resolved;
        }

        try {
            return $this->driver->specializeDecoder($resolved);
        } catch (NotSupportedException $e) {
            throw new DriverException(
                'Failed to resolved decoder ' .
                    (is_string($decoder) ? $decoder : $decoder::class) . ' with driver ' . $this->driver::class,
                previous: $e,
            );
        }
    }
}
