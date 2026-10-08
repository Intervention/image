<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Drivers\Gd\ColorProcessor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RotateModifier as GenericRotateModifier;
use Intervention\Image\Size;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     * @throws DriverException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->backgroundColor();

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $background);
        }

        return $image;
    }

    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws DriverException
     */
    protected function modifyFrame(FrameInterface $frame, ColorInterface $background): void
    {
        // normalize color to rgb colorspace
        $background = $background->toColorspace(Rgb::class);

        if (!$background instanceof RgbColor) {
            throw new ModifierException('Failed to normalize background color to RGB color space');
        }

        if ($this->isNoOp($frame->native(), $background)) {
            $this->resetFrame($frame->native(), $background);

            return;
        }

        // remove a possible transparent color, libgd fills its pixels with the
        // background color when rotating and imagecopy() would skip them
        $source = $this->withoutTransparentColor($frame->native(), $background);

        // rotate against background color, so the new areas match the edges of
        // the rotated image, the bundled GD of PHP turns pixels of the transparent
        // color opaque black when rotating by 0 degrees, so the source image is
        // used directly in this case
        $rotated = $this->rotationAngle() === 0.0 ? $source : imagerotate(
            $source,
            $this->rotationAngle() * -1,
            (new ColorProcessor())->export($background),
        );

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), new Size(
            imagesx($rotated),
            imagesy($rotated),
        ), $background);

        // place rotated image on new gd image without blending, as the rotated
        // image already contains the background in the new areas
        imagealphablending($modified, false);
        imagecopy(
            $modified,
            $rotated,
            0,
            0,
            0,
            0,
            imagesx($rotated),
            imagesy($rotated),
        );
        imagealphablending($modified, true);

        $frame->setNative($modified);
    }

    /**
     * Determine if rotating the given image by the current angle leaves its
     * pixels untouched, so the costly rotation on a new canvas can be skipped.
     *
     * Only the frame settings of the new canvas (see Cloner::cloneEmpty()) are
     * applied then. This requires a truecolor image and is not possible if an
     * existing transparent color would have to be removed, as GD can not unset
     * it.
     */
    private function isNoOp(GdImage $gd, RgbColor $background): bool
    {
        if ($this->rotationAngle() !== 0.0) {
            return false;
        }

        if (!imageistruecolor($gd)) {
            return false;
        }

        if (!$background->isClear() && imagecolortransparent($gd) !== -1) {
            return false;
        }

        return true;
    }

    /**
     * Return the given image as truecolor image without transparent color, pixels
     * of the transparent color are turned into fully transparent pixels. The given
     * image is returned unchanged if there is nothing to convert.
     *
     * @throws DriverException
     */
    private function withoutTransparentColor(GdImage $gd, RgbColor $background): GdImage
    {
        if (imageistruecolor($gd) && imagecolortransparent($gd) === -1) {
            return $gd;
        }

        $width = imagesx($gd);
        $height = imagesy($gd);
        $converted = imagecreatetruecolor($width, $height);
        if ($converted === false) {
            throw new DriverException('Failed to create new image while rotating');
        }

        // pixels of the transparent color are skipped when copying and keep the
        // fully transparent fill
        imagealphablending($converted, false);
        imagesavealpha($converted, true);
        imagefilledrectangle($converted, 0, 0, $width - 1, $height - 1, imagecolorallocatealpha(
            $converted,
            $background->red()->value(),
            $background->green()->value(),
            $background->blue()->value(),
            127,
        ));
        imagecopy($converted, $gd, 0, 0, 0, 0, $width, $height);

        return $converted;
    }

    /**
     * Apply the same frame settings to the given image as a rotation would do
     * by placing the result on a new canvas.
     *
     * @throws DriverException
     */
    private function resetFrame(GdImage $gd, RgbColor $background): void
    {
        imagealphablending($gd, true);
        imagesavealpha($gd, true);

        if ($background->isClear()) {
            imagecolortransparent($gd, (new ColorProcessor())->export($background));
        }
    }
}
