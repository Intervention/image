<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use ImagickPixelException;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\OrientModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;

class NativeObjectDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * Media types by ImageMagick format name. Imagick::getImageMimeType() is
     * expensive (~0.3ms per call) and only depends on the image format.
     *
     * @var array<string, string>
     */
    private static array $mediaTypes = [];

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $input instanceof Imagick;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws StateException
     * @throws DriverException
     * @throws ImageDecoderException
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!$input instanceof Imagick) {
            throw new InvalidArgumentException('Image source must be an instance of Imagick');
        }

        // work on a copy so the given object is never modified, which is cheap
        // as ImageMagick shares the pixel data of clones until it is changed
        $input = clone $input;

        try {
            $originalMimeType = self::$mediaTypes[$input->getImageFormat()] ??= $input->getImageMimeType();
        } catch (ImagickException $e) {
            throw new ImageDecoderException('Failed to retrieve image media type', previous: $e);
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        try {
            if ($input->getImageFormat() !== 'JPEG') {
                $input = $this->requiresCoalescing($input)
                    ? $input->coalesceImages()
                    : $this->resetVirtualCanvas($input);
            }
        } catch (ImagickException | ImagickPixelException $e) {
            throw new DriverException('Failed to coalesce image', previous: $e);
        }

        // turn images with colorspace 'GRAY' into 'SRGB' to avoid working on
        // grayscale colorspace images as this results images loosing color
        // information when placed into this image.
        try {
            if ($input->getImageColorspace() === Imagick::COLORSPACE_GRAY) {
                $input->setImageColorspace(Imagick::COLORSPACE_SRGB);

                // make sure gray ICC profile are no longer kept in SRGB image
                $profiles = $input->getImageProfiles('icc');
                if (substr($profiles['icc'] ?? '', 16, 4) === 'GRAY') {
                    $input->removeImageProfile('icc');
                }
            }

            // AVIF/HEIF store their pixels in a luma/chroma (YCbCr) colorspace.
            // Recent ImageMagick normalizes this to sRGB on decode, but older
            // releases report the image as YCbCr, which leaves every later color
            // operation (colorspace analysis, pixel reads) working on raw
            // luma/chroma values. Convert it to sRGB so colors are correct.
            if ($input->getImageColorspace() === Imagick::COLORSPACE_YCBCR) {
                $input->transformImageColorspace(Imagick::COLORSPACE_SRGB);
            }
        } catch (ImagickException $e) {
            throw new DriverException('Failed to convert image to sRGB', previous: $e);
        }

        // create image object
        $image = new Image($this->driver(), new Core($input));

        // If autoOrientation is disabled, automatic image alignment should be prevented.
        // Therefore, it is set to "undefined" here. To still be able to correct the
        // orientation manually later, we save the original value.
        if ($this->driver()->config()->autoOrientation === false) {
            try {
                $image->core()->meta()->set('originalImageOrientation', $input->getImageOrientation());
                $input->setImageOrientation(Imagick::ORIENTATION_UNDEFINED);
            } catch (ImagickException $e) {
                throw new ImageDecoderException(
                    'Failed to set adjust image orientation',
                    previous: $e,
                );
            }
        }

        // discard animation depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $image->modify(new RemoveAnimationModifier());
        }

        // adjust image rotation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new OrientModifier());
        }

        // set media type on origin
        $image->origin()->setMediaType($originalMimeType);

        return $image;
    }

    /**
     * Determine if the given Imagick object must be coalesced.
     *
     * coalesceImages() renders every frame onto a canvas of the full virtual
     * page size, which is required for animations but results in a costly full
     * copy of the pixel data (~10-20ms for a 1920x1280 image) that is useless
     * when the image is a single frame already covering its whole page.
     *
     * @throws ImagickException
     */
    private function requiresCoalescing(Imagick $imagick): bool
    {
        // animations must be rendered onto their virtual canvas frame by frame
        if ($imagick->getNumberImages() !== 1) {
            return true;
        }

        // filling the canvas converts palette images to truecolor and might
        // convert other colorspaces to srgb, which must be preserved
        if ($imagick->getImageColorspace() !== Imagick::COLORSPACE_SRGB) {
            return true;
        }

        // IMGTYPE_TRUECOLORMATTE is used as IMGTYPE_TRUECOLORALPHA is not defined with ImageMagick 6
        if (!in_array($imagick->getImageType(), [Imagick::IMGTYPE_TRUECOLOR, Imagick::IMGTYPE_TRUECOLORMATTE], true)) {
            return true;
        }

        // the frame must cover its whole virtual canvas (page size 0 means undefined)
        $page = $imagick->getImagePage();

        return $page['x'] !== 0
            || $page['y'] !== 0
            || !in_array($page['width'], [0, $imagick->getImageWidth()], true)
            || !in_array($page['height'], [0, $imagick->getImageHeight()], true);
    }

    /**
     * Apply the same frame attributes to the given single frame image that
     * coalesceImages() would set, without copying its pixel data.
     *
     * @throws ImagickException
     * @throws ImagickPixelException
     */
    private function resetVirtualCanvas(Imagick $imagick): Imagick
    {
        $imagick->setImagePage($imagick->getImageWidth(), $imagick->getImageHeight(), 0, 0);
        $imagick->setImageDispose(Imagick::DISPOSE_NONE);

        // coalescing keeps the background color but makes it fully transparent
        $background = $imagick->getImageBackgroundColor();
        $background->setColorValue(Imagick::COLOR_ALPHA, 0);
        $imagick->setImageBackgroundColor($background);

        return $imagick;
    }
}
