<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\MissingDependencyException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Format;
use Intervention\Image\FileExtension;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\CoreInterface;
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
     *
     * @codeCoverageIgnore
     */
    public function checkHealth(): void
    {
        if (!extension_loaded('imagick') || !class_exists('Imagick')) {
            throw new MissingDependencyException(
                'Imagick PHP extension must be installed to use this driver'
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     *
     * @throws DriverException
     */
    public function createImage(int $width, int $height): ImageInterface
    {
        try {
            $background = new ImagickPixel('rgba(255, 255, 255, 0)');

            $imagick = new Imagick();
            $imagick->newImage($width, $height, $background, 'png');
            $this->applyDefaultSettings($imagick);
        } catch (ImagickException $e) {
            throw new DriverException('Failed to create new image', previous: $e);
        }

        return new Image($this, new Core($imagick));
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createCore()
     */
    public function createCore(array $frames): CoreInterface
    {
        $core = new Core(new Imagick());

        foreach ($frames as $frame) {
            $core->add($frame);
        }

        $this->applyDefaultSettings($core->native());

        return $core;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::colorProcessor()
     */
    public function colorProcessor(ImageInterface $image): ColorProcessorInterface
    {
        return new ColorProcessor($image->colorspace());
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
        } catch (InvalidArgumentException) {
            return false;
        }

        return count(Imagick::queryFormats($format->name)) >= 1;
    }

    /**
     * Return version of ImageMagick library
     *
     * @throws DriverException
     */
    public function version(): string
    {
        $pattern = '/^ImageMagick (?P<version>(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)' .
            '(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?' .
            '(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?)/';

        if (preg_match($pattern, Imagick::getVersion()['versionString'], $matches) !== 1) {
            throw new DriverException('Unable to read ImageMagick version number');
        }

        return $matches['version'];
    }

    /**
     * Apply default settings for native image object.
     *
     * @throws DriverException
     */
    private function applyDefaultSettings(Imagick $imagick): Imagick
    {
        try {
            $background = new ImagickPixel('rgba(255, 255, 255, 0)');

            $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
            $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
            $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
            $imagick->setImageResolution(72, 72);
            $imagick->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
            $imagick->setImageBackgroundColor($background);
            $imagick->setImageIterations(0);

            return $imagick;
        } catch (ImagickException $e) {
            throw new DriverException('Failed to apply default image settings', previous: $e);
        }
    }
}
