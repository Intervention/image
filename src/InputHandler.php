<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder as CmykStringColorDecoder;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder as HslStringColorDecoder;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder as HsvStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\ColorObjectDecoder;
use Intervention\Image\Decoders\DataUriImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Decoders\FilePointerImageDecoder;
use Intervention\Image\Decoders\ImageObjectDecoder;
use Intervention\Image\Decoders\NativeObjectDecoder;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\InputHandlerInterface;

class InputHandler implements InputHandlerInterface
{
    /**
     * Decoder classnames in hierarchical order
     *
     * @var array<string|DecoderInterface>
     */
    protected array $decoders = [
        NativeObjectDecoder::class,
        ImageObjectDecoder::class,
        ColorObjectDecoder::class,
        RgbHexColorDecoder::class,
        RgbStringColorDecoder::class,
        CmykStringColorDecoder::class,
        HsvStringColorDecoder::class,
        HslStringColorDecoder::class,
        TransparentColorDecoder::class,
        HtmlColornameDecoder::class,
        FilePointerImageDecoder::class,
        FilePathImageDecoder::class,
        SplFileInfoImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
    ];

    /**
     * Driver with which the decoder classes are specialized
     *
     * @var null|DriverInterface
     */
    protected ?DriverInterface $driver = null;

    /**
     * Create new input handler instance with given decoder classnames
     *
     * @param array<string|DecoderInterface> $decoders
     * @param DriverInterface $driver
     * @return void
     */
    public function __construct(array $decoders = [], ?DriverInterface $driver = null)
    {
        $this->decoders = count($decoders) ? $decoders : $this->decoders;
        $this->driver = $driver;
    }

    /**
     * Static factory method
     *
     * @param array<string|DecoderInterface> $decoders
     * @param null|DriverInterface $driver
     * @return InputHandler
     */
    public static function withDecoders(array $decoders, ?DriverInterface $driver = null): self
    {
        return new self($decoders, $driver);
    }

    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     */
    public function handle($input): ImageInterface|ColorInterface
    {
        foreach ($this->decoders as $decoder) {
            // resolve driver specialized decoder
            $decoder = $this->resolve($decoder);

            try {
                return $decoder->decode($input);
            } catch (DecoderException $e) {
                // try next decoder
            }
        }

        throw new DecoderException(isset($e) ? $e->getMessage() : '');
    }

    /**
     * Resolve the given classname to an decoder object
     *
     * @param string|DecoderInterface $decoder
     * @throws DriverException
     * @throws NotSupportedException
     * @return DecoderInterface
     */
    private function resolve(string|DecoderInterface $decoder): DecoderInterface
    {
        if (($decoder instanceof DecoderInterface) && empty($this->driver)) {
            return $decoder;
        }

        if (($decoder instanceof DecoderInterface) && !empty($this->driver)) {
            return $this->driver->specialize($decoder);
        }

        if (empty($this->driver)) {
            return new $decoder();
        }

        return $this->driver->specialize(new $decoder());
    }
}
