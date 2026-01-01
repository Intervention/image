<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Closure;
use Countable;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\FileExtension;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\MediaType;
use Intervention\Image\Origin;
use Intervention\Image\Alignment;
use Intervention\Image\Fraction;
use Intervention\Image\Format;
use IteratorAggregate;

/**
 * @extends IteratorAggregate<FrameInterface>
 */
interface ImageInterface extends IteratorAggregate, Countable
{
    /**
     * Return driver of current image.
     */
    public function driver(): DriverInterface;

    /**
     * Return core of current image.
     */
    public function core(): CoreInterface;

    /**
     * Return the origin of the image.
     */
    public function origin(): Origin;

    /**
     * Set the origin of the image.
     */
    public function setOrigin(Origin $origin): self;

    /**
     * Return width of current image.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-width
     */
    public function width(): int;

    /**
     * Return height of current image.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-height
     */
    public function height(): int;

    /**
     * Return size of current image.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-image-size-as-an-object
     */
    public function size(): SizeInterface;

    /**
     * Save the image to the specified path in the file system. If no path is
     * given, the image will be saved at its original location.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode--save-combined
     */
    public function save(?string $path = null, mixed ...$options): self;

    /**
     * Apply given modifier to current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/custom-modifiers
     */
    public function modify(ModifierInterface $modifier): self;

    /**
     * Analyzer current image with given analyzer.
     */
    public function analyze(AnalyzerInterface $analyzer): mixed;

    /**
     * Determine if current image is animated.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#check-the-current-image-instance-for-animation
     */
    public function isAnimated(): bool;

    /**
     * Remove all frames but keep the one at the specified position.
     *
     * It is possible to specify the position as integer or string values.
     * With the former, the exact position passed is searched for, while
     * string values must represent a percentage value between '0%' and '100%'
     * and the respective frame position is only determined approximately.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#remove-animation
     */
    public function removeAnimation(int|string $position = 0): self;

    /**
     * Extract animation frames based on given values and discard the rest.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): self;

    /**
     * Return loop count of animated image.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#read-the-animation-iteration-count
     */
    public function loops(): int;

    /**
     * Set loop count of animated image.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     */
    public function setLoops(int $loops): self;

    /**
     * Return exif data of current image.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#exif-information
     */
    public function exif(?string $query = null): mixed;

    /**
     * Set exif data for the image object.
     */
    public function setExif(CollectionInterface $exif): self;

    /**
     * Return image resolution/density.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     */
    public function resolution(): ResolutionInterface;

    /**
     * Set the image resolution in DPI.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     */
    public function setResolution(float $x, float $y): self;

    /**
     * Get the colorspace of the image.
     *
     * @link https://image.intervention.io/v3/basics/colors#read-the-image-colorspace
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Transform image to given colorspace.
     *
     * @link https://image.intervention.io/v3/basics/colors#change-the-image-colorspace
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Return color of pixel at given position on given frame position.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     */
    public function pickColor(int $x, int $y, int $frame = 0): ColorInterface;

    /**
     * Return all colors of pixel at given position for all frames of image.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     */
    public function pickColors(int $x, int $y): CollectionInterface;

    /**
     * Return color that is mixed with transparent areas when converting to a
     * format which does not support transparency.
     */
    public function backgroundColor(): ColorInterface;

    /**
     * Set the background color to be used with self::background().
     *
     * Settting the background color will have no effect unless image is
     * converted into a format which does not support transparency or
     * self::background() is used.
     */
    public function setBackgroundColor(string|ColorInterface $color): self;

    /**
     * Replace transparent areas of the image with given color or currently
     * configured background color.
     */
    public function background(null|string|ColorInterface $color = null): self;

    /**
     * Retrieve ICC color profile of image.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function profile(): ProfileInterface;

    /**
     * Set given icc color profile to image.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function setProfile(ProfileInterface $profile): self;

    /**
     * Remove ICC color profile from the current image.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function removeProfile(): self;

    /**
     * Apply color quantization to the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#reduce-colors
     */
    public function reduceColors(int $limit, mixed $background = 'transparent'): self;

    /**
     * Sharpen the current image with given strength.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#sharpening-effect
     */
    public function sharpen(int $amount = 10): self;

    /**
     * Turn image into a greyscale version.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#convert-image-to-a-greyscale-version
     */
    public function greyscale(): self;

    /**
     * Adjust brightness of the current image by given level.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-brightness
     */
    public function brightness(int $level): self;

    /**
     * Adjust color contrast of the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-contrast
     */
    public function contrast(int $level): self;

    /**
     * Apply gamma correction on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#gamma-correction
     */
    public function gamma(float $gamma): self;

    /**
     * Adjust the intensity of the RGB color channels.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#color-correction
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): self;

    /**
     * Mirror the current image horizontally.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#mirror-image-vertically
     */
    public function flip(): self;

    /**
     * Mirror the current image vertically.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#mirror-image-horizontally
     */
    public function flop(): self;

    /**
     * Blur current image by given strength.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#blur-effect
     */
    public function blur(int $amount = 5): self;

    /**
     * Invert the colors of the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#invert-colors
     */
    public function invert(): self;

    /**
     * Apply pixelation filter effect on current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#pixelation-effect
     */
    public function pixelate(int $size): self;

    /**
     * Rotate current image by given angle.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-rotation
     *
     * @param string $background
     */
    public function rotate(float $angle, mixed $background = null): self;

    /**
     * Rotate the image to be upright according to exif information.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-orientation-according-to-exif-data
     */
    public function orient(): self;

    /**
     * Draw text on image.
     *
     * @link https://image.intervention.io/v3/modifying-images/text-fonts
     */
    public function text(string $text, int $x, int $y, callable|Closure|FontInterface $font): self;

    /**
     * Resize image to the given width and/or height.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#simple-image-resizing
     */
    public function resize(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize image to the given width and/or height without exceeding the original dimensions.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-without-exceeding-the-original-size
     */
    public function resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize image to the given width and/or height and keep the original aspect ratio.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-images-proportionally
     */
    public function scale(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize image to the given width and/or height, keep the original aspect ratio
     * and do not exceed the original image width or height
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#scale-images-but-do-not-exceed-the-original-size
     */
    public function scaleDown(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Takes the specified width and height and scales them to the largest
     * possible size that fits within the original size. This scaled size is
     * then positioned on the original and cropped, before this result is resized
     * to the desired size using the arguments.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cropping--resizing-combined
     */
    public function cover(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): self;

    /**
     * Same as cover() but do not exceed the original image size.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#fitted-resizing-without-exceeding-the-original-size
     */
    public function coverDown(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): self;

    /**
     * Resize the boundaries of the current image to given width and height.
     * An anchor position can be defined to determine where the original image
     * is fixed. A background color can be passed to define the color of the
     * new emerging areas.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-without-resampling-the-original-image
     */
    public function resizeCanvas(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        mixed $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Resize canvas in the same way as resizeCanvas() but takes relative values
     * for the width and height, which will be added or subtracted to the
     * original image size.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-relative-to-the-original
     */
    public function resizeCanvasRelative(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        mixed $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Padded resizing means that the original image is scaled until it fits the
     * defined target size with unchanged aspect ratio. The original image is
     * not scaled up but only down.
     *
     * Compared to the cover() method, this method does not create cropped areas,
     * but possibly new empty areas on the sides of the result image. These are
     * filled with the specified background color.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resizing--padding-combined
     *
     * @param string $background
     */
    public function pad(
        int|Fraction $width,
        int|Fraction $height,
        mixed $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * This method does the same as pad(), but the original image is also scaled
     * up if the target size exceeds the original size.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#padded-resizing-with-upscaling
     *
     * @param string $background
     */
    public function contain(
        int|Fraction $width,
        int|Fraction $height,
        mixed $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Cut out a rectangular part of the current image with given width and
     * height at a given position. Define optional x,y offset coordinates
     * to move the cutout by the given amount of pixels.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cut-out-a-rectangular-part
     */
    public function crop(
        int|Fraction $width,
        int|Fraction $height,
        int $x = 0,
        int $y = 0,
        mixed $background = null,
        string|Alignment $alignment = Alignment::TOP_LEFT
    ): self;

    /**
     * Trim the image by removing border areas of similar color within a the given tolerance.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#remove-border-areas-in-similar-color
     */
    public function trim(int $tolerance = 0): self;

    /**
     * Place another image into the current image instance
     *
     * @link https://image.intervention.io/v3/modifying-images/inserting#insert-images
     */
    public function place(
        mixed $element,
        string|Alignment $alignment = Alignment::TOP_LEFT,
        int $x = 0,
        int $y = 0,
        int $opacity = 100
    ): self;

    /**
     * Fill image with given color.
     *
     * If an optional position is specified for the filling process ln the form
     * of x and y coordinates, the process is executed as flood fill. This means
     * that the color at the specified position is taken as a reference and all
     * adjacent pixels are also filled with the filling color.
     *
     * If no coordinates are specified, the entire image area is filled.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#fill-images-with-color
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): self;

    /**
     * Draw a single pixel at given position defined by the coordinates x and y in a given color.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-pixels
     */
    public function drawPixel(int $x, int $y, mixed $color): self;

    /**
     * Draw a rectangle on the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-rectangle
     */
    public function drawRectangle(int $x, int $y, callable|Closure|Rectangle $init): self;

    /**
     * Draw ellipse on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-ellipses
     */
    public function drawEllipse(int $x, int $y, callable|Closure|Ellipse $init): self;

    /**
     * Draw circle on the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-circle
     */
    public function drawCircle(int $x, int $y, callable|Closure|Circle $init): self;

    /**
     * Draw a polygon on the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-polygon
     */
    public function drawPolygon(callable|Closure|Polygon $init): self;

    /**
     * Draw a line on the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-line
     */
    public function drawLine(callable|Closure|Line $init): self;

    /**
     * Draw a bezier curve on the current image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-bezier-curves
     */
    public function drawBezier(callable|Closure|Bezier $init): self;

    /**
     * Encode the current image with the given encoder.
     */
    public function encode(string|EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface;

    /**
     * Encode the current image by resolving the encoder using one of the given arguments.
     *
     * - Image format
     * - Media (MIME) type
     * - File extension
     * - File path
     */
    public function encodeUsing(
        null|Format $format = null,
        null|string|MediaType $mediaType = null,
        null|string|FileExtension $extension = null,
        null|string $path = null,
        mixed ...$options,
    ): EncodedImageInterface;
}
