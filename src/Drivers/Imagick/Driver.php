<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Format;
use Intervention\Image\FileExtension;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FontProcessorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\MediaType;

class Driver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::id()
     */
    public function id(): string
    {
        return 'Imagick';
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::checkHealth()
     * @codeCoverageIgnore
     */
    public function checkHealth(): void
    {
        if (!extension_loaded('imagick') || !class_exists('Imagick')) {
            throw new DriverException(
                'Imagick PHP extension must be installed to use this driver.'
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     */
    public function createImage(int $width, int $height): ImageInterface
    {
        $background = new ImagickPixel('rgba(255, 255, 255, 0)');

        $imagick = new Imagick();
        $imagick->newImage($width, $height, $background, 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->setImageResolution(96, 96);
        $imagick->setImageBackgroundColor($background);

        return new Image($this, new Core($imagick));
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     * @see DriverInterface::createAnimation()
     */
    public function createAnimation(callable $init): ImageInterface
    {
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        $animation = new class ($this, $imagick)
        {
            public function __construct(
                protected DriverInterface $driver,
                public Imagick $imagick
            ) {
            }

            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $native = $this->driver->handleInput($source)->core()->native();
                $native->setImageDelay(intval(round($delay * 100)));

                $this->imagick->addImage($native);

                return $this;
            }

            /**
             * @throws RuntimeException
             */
            public function __invoke(): ImageInterface
            {
                return new Image(
                    $this->driver,
                    new Core($this->imagick)
                );
            }
        };

        $init($animation);

        return call_user_func($animation);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::colorProcessor()
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface
    {
        return new ColorProcessor($colorspace);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::fontProcessor()
     */
    public function fontProcessor(): FontProcessorInterface
    {
        return new FontProcessor();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::supports()
     */
    public function supports(string|Format|FileExtension|MediaType $identifier): bool
    {
        try {
            $format = Format::create($identifier);
        } catch (NotSupportedException) {
            return false;
        }

        return count(Imagick::queryFormats($format->name)) >= 1;
    }
}
