<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\MissingDependencyException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Format;
use Intervention\Image\FileExtension;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
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
     *
     * @codeCoverageIgnore
     */
    public function checkHealth(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new MissingDependencyException(
                'GD PHP extension must be installed to use this driver'
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
        if ($width < 1 || $height < 1) {
            throw new InvalidArgumentException('Invalid image size. Only use int<1, max>');
        }

        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        if (!($data instanceof GDImage)) {
            throw new DriverException('Failed to create new image');
        }

        $alpha = imagesavealpha($data, true);
        if ($alpha === false) {
            throw new DriverException('Failed to flag image to save alpha channel');
        }

        $background = imagecolorallocatealpha($data, 255, 255, 255, 127);
        if ($background === false) {
            throw new DriverException('Failed to create image background color');
        }

        $alphablending = imagealphablending($data, false);
        if ($alphablending === false) {
            throw new DriverException('Failed to set image alpha blending');
        }

        $fill = imagefill($data, 0, 0, $background);
        if ($fill === false) {
            throw new DriverException('Failed to fill image initially with transparency');
        }

        imagecolortransparent($data, $background);

        $resolution = imageresolution($data, 72, 72);
        if ($resolution === false) {
            throw new DriverException('Failed to set initial image resolution');
        }

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
     * @see DriverInterface::createAnimation()
     */
    public function createAnimation(callable $init): ImageInterface
    {
        $animation = new AnimationFactory($this);
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

    /**
     * Return version of GD library
     */
    public function version(): string
    {
        return gd_info()['GD Version'];
    }
}
