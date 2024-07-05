<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Exceptions\DriverException;
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
        return 'GD';
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::checkHealth()
     * @codeCoverageIgnore
     */
    public function checkHealth(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new DriverException(
                'GD PHP extension must be installed to use this driver.'
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
        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        imagesavealpha($data, true);
        $background = imagecolorallocatealpha($data, 255, 255, 255, 127);
        imagealphablending($data, false);
        imagefill($data, 0, 0, $background);
        imagecolortransparent($data, $background);

        return new Image(
            $this,
            new Core([
                new Frame($data)
            ])
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     * @see DriverInterface::createAnimation()
     */
    public function createAnimation(callable $init): ImageInterface
    {
        $animation = new class ($this)
        {
            public function __construct(
                protected DriverInterface $driver,
                public Core $core = new Core()
            ) {
            }

            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $this->core->add(
                    $this->driver->handleInput($source)->core()->first()->setDelay($delay)
                );

                return $this;
            }

            /**
             * @throws RuntimeException
             */
            public function __invoke(): ImageInterface
            {
                return new Image(
                    $this->driver,
                    $this->core
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
        return match (Format::tryCreate($identifier)) {
            Format::JPEG => boolval(imagetypes() & IMG_JPEG),
            Format::WEBP => boolval(imagetypes() & IMG_WEBP),
            Format::GIF => boolval(imagetypes() & IMG_GIF),
            Format::PNG => boolval(imagetypes() & IMG_PNG),
            Format::AVIF => boolval(imagetypes() & IMG_AVIF),
            Format::BMP => boolval(imagetypes() & IMG_BMP),
            default => false,
        };
    }
}
