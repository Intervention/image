<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Alignment;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Drivers\Gd\ColorProcessor;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Polygon;
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

        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->native())) {
            -1 => imagecolorallocatealpha(
                $frame->native(),
                $background->red()->value(),
                $background->green()->value(),
                $background->blue()->value(),
                127,
            ),
            default => $transparent,
        };

        // rotate original image against transparent background, the bundled GD
        // of PHP turns pixels of the transparent color opaque black when rotating
        // by 0 degrees, so the original image is used directly in this case
        $rotated = $this->rotationAngle() === 0.0 ? $frame->native() : imagerotate(
            $frame->native(),
            $this->rotationAngle() * -1,
            $transparent,
        );

        // create size from original after rotation
        $container = (new Size(
            imagesx($rotated),
            imagesy($rotated),
        ))->movePivot(Alignment::CENTER);

        // create size from original and rotate points
        $cutout = Polygon::fromSize(new Size(
            imagesx($frame->native()),
            imagesy($frame->native()),
            $container->pivot(),
        ))->alignHorizontally(Alignment::CENTER)
            ->alignVertically(Alignment::CENTER)
            ->rotate($this->rotationAngle());

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $container, $background);

        // draw the cutout on new gd image to have a fully transparent
        // background where the rotated image will be placed, fully
        // transparent pixels of the rotated image will keep this color
        imagealphablending($modified, false);
        imagefilledpolygon(
            $modified,
            $cutout->toArray(),
            imagecolorallocatealpha(
                $modified,
                $background->red()->value(),
                $background->green()->value(),
                $background->blue()->value(),
                127,
            ),
        );

        // place rotated image on new gd image
        imagealphablending($modified, true);
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
