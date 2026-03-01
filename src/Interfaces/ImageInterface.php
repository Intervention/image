<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\FileExtension;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\MediaType;
use Intervention\Image\Alignment;
use Intervention\Image\Direction;
use Intervention\Image\Fraction;
use Intervention\Image\Format;
use IteratorAggregate;

/**
 * @extends IteratorAggregate<FrameInterface>
 */
interface ImageInterface extends IteratorAggregate, Countable
{
    /**
     * Return the image driver.
     */
    public function driver(): DriverInterface;

    /**
     * Return the image core.
     */
    public function core(): CoreInterface;

    /**
     * Return the image origin.
     */
    public function origin(): OriginInterface;

    /**
     * Set the image origin.
     */
    public function setOrigin(OriginInterface $origin): self;

    /**
     * Return the image width in pixels.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-width
     */
    public function width(): int;

    /**
     * Return the image height in pixels.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-height
     */
    public function height(): int;

    /**
     * Return the image size as an object.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-image-size-as-an-object
     */
    public function size(): SizeInterface;

    /**
     * Save the image to the given path. If no path is given, the image will
     * be saved at its original location.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode--save-combined
     */
    public function save(?string $path = null, mixed ...$options): self;

    /**
     * Apply the given modifier to the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/custom-modifiers
     */
    public function modify(ModifierInterface $modifier): self;

    /**
     * Analyze the image with the given analyzer.
     */
    public function analyze(AnalyzerInterface $analyzer): mixed;

    /**
     * Determine if the image is animated.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#check-the-current-image-instance-for-animation
     */
    public function isAnimated(): bool;

    /**
     * Remove all frames but keep the one at the specified position.
     *
     * Integer values select the exact frame position, while string values
     * represent a percentage between '0%' and '100%' to determine the
     * approximate frame position.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#remove-animation
     */
    public function removeAnimation(int|string $position = 0): self;

    /**
     * Keep only the frames defined by offset and length, discarding the rest.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): self;

    /**
     * Return the animation loop count.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#read-the-animation-iteration-count
     */
    public function loops(): int;

    /**
     * Set the animation loop count.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     */
    public function setLoops(int $loops): self;

    /**
     * Return the EXIF data of the image.
     *
     * @link https://image.intervention.io/v3/basics/meta-information#exif-information
     */
    public function exif(?string $query = null): mixed;

    /**
     * Set the EXIF data of the image.
     */
    public function setExif(CollectionInterface $exif): self;

    /**
     * Return the image resolution in DPI.
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
     * Return the image colorspace.
     *
     * @link https://image.intervention.io/v3/basics/colors#read-the-image-colorspace
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Transform the image to the given colorspace.
     *
     * @link https://image.intervention.io/v3/basics/colors#change-the-image-colorspace
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Return the color of the pixel at the given position and frame.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     */
    public function colorAt(int $x, int $y, int $frame = 0): ColorInterface;

    /**
     * Return the colors of the pixel at the given position across all frames.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     */
    public function colorsAt(int $x, int $y): CollectionInterface;

    /**
     * Return the background color used to replace transparent areas during
     * encoding to formats that do not support transparency.
     */
    public function backgroundColor(): ColorInterface;

    /**
     * Set the background color used to replace transparent areas during
     * encoding to formats that do not support transparency.
     */
    public function setBackgroundColor(string|ColorInterface $color): self;

    /**
     * Replace transparent areas with the given color or the configured
     * background color.
     */
    public function fillTransparentAreas(null|string|ColorInterface $color = null): self;

    /**
     * Return the ICC color profile.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function profile(): ProfileInterface;

    /**
     * Set the ICC color profile.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function setProfile(ProfileInterface $profile): self;

    /**
     * Remove the ICC color profile.
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     */
    public function removeProfile(): self;

    /**
     * Reduce the number of colors in the image to the given limit.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#reduce-colors
     */
    public function reduceColors(int $limit, null|string|ColorInterface $background = null): self;

    /**
     * Sharpen the image by the given level.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#sharpening-effect
     */
    public function sharpen(int $level = 10): self;

    /**
     * Turn the image into a grayscale version.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#convert-image-to-a-grayscale-version
     */
    public function grayscale(): self;

    /**
     * Adjust the image brightness by the given level.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-brightness
     */
    public function brightness(int $level): self;

    /**
     * Adjust the image contrast by the given level.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-contrast
     */
    public function contrast(int $level): self;

    /**
     * Apply gamma correction to the image.
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
     * Mirror the image in the given direction.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects
     */
    public function flip(Direction $direction = Direction::HORIZONTAL): self;

    /**
     * Apply a blur effect with the given level.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#blur-effect
     */
    public function blur(int $level = 5): self;

    /**
     * Invert the image colors.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#invert-colors
     */
    public function invert(): self;

    /**
     * Apply a pixelation effect with the given tile size.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#pixelation-effect
     */
    public function pixelate(int $size): self;

    /**
     * Rotate the image clockwise by the given angle.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-rotation
     */
    public function rotate(float $angle, null|string|ColorInterface $background = null): self;

    /**
     * Orient the image upright based on EXIF data.
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-orientation-according-to-exif-data
     */
    public function orient(): self;

    /**
     * Draw text on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/text-fonts
     */
    public function text(string $text, int $x, int $y, callable|FontInterface $font): self;

    /**
     * Resize the image to the given width and/or height.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#simple-image-resizing
     */
    public function resize(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize the image to the given width and/or height without exceeding
     * the original dimensions.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-without-exceeding-the-original-size
     */
    public function resizeDown(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize the image to the given width and/or height while maintaining
     * the aspect ratio.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-images-proportionally
     */
    public function scale(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Resize the image to the given width and/or height while maintaining
     * the aspect ratio and without exceeding the original dimensions.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#scale-images-but-do-not-exceed-the-original-size
     */
    public function scaleDown(null|int|Fraction $width = null, null|int|Fraction $height = null): self;

    /**
     * Crop and resize the image to cover the given dimensions exactly.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cropping--resizing-combined
     */
    public function cover(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): self;

    /**
     * Crop and resize the image to cover the given dimensions without
     * exceeding the original dimensions.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#fitted-resizing-without-exceeding-the-original-size
     */
    public function coverDown(
        int|Fraction $width,
        int|Fraction $height,
        string|Alignment $alignment = Alignment::CENTER,
    ): self;

    /**
     * Resize the image canvas to the given width and height without resampling
     *
     * The alignment position defines where the original image is fixed,
     * and new areas are filled with the given background color.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-without-resampling-the-original-image
     */
    public function resizeCanvas(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Resize the image canvas by adding or subtracting the given width and
     * height relative to the original dimensions.
     *
     * The alignment position defines where the original image is fixed,
     * and new areas are filled with the given background color.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-relative-to-the-original
     */
    public function resizeCanvasRelative(
        null|int|Fraction $width = null,
        null|int|Fraction $height = null,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Resize the image to fit within the given dimensions while maintaining
     * the aspect ratio. New areas are filled with the given background color.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#padded-resizing-with-upscaling
     */
    public function contain(
        int|Fraction $width,
        int|Fraction $height,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Resize the image to fit within the given dimensions while maintaining
     * the aspect ratio and without exceeding the original dimensions. New
     * areas are filled with the given background color.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resizing--padding-combined
     */
    public function containDown(
        int|Fraction $width,
        int|Fraction $height,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::CENTER
    ): self;

    /**
     * Cut out a rectangular part of the image with the given width and height
     * at the given alignment position offset by x and y.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cut-out-a-rectangular-part
     */
    public function crop(
        int|Fraction $width,
        int|Fraction $height,
        int $x = 0,
        int $y = 0,
        null|string|ColorInterface $background = null,
        string|Alignment $alignment = Alignment::TOP_LEFT
    ): self;

    /**
     * Trim border areas of similar color within the given tolerance.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#remove-border-areas-in-similar-color
     */
    public function trim(int $tolerance = 0): self;

    /**
     * Insert another image at the given position relative to the alignment position.
     *
     * @link https://image.intervention.io/v3/modifying-images/inserting#insert-images
     */
    public function insert(
        string|self $image,
        int $x = 0,
        int $y = 0,
        string|Alignment $alignment = Alignment::TOP_LEFT,
        int $opacity = 100
    ): self;

    /**
     * Fill the image with the given color. If coordinates are specified, the
     * fill is applied as a flood fill starting at that position. Otherwise
     * the entire image area is filled.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#fill-images-with-color
     */
    public function fill(string|ColorInterface $color, ?int $x = null, ?int $y = null): self;

    /**
     * Draw a single pixel at the given position in the given color.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-pixels
     */
    public function drawPixel(int $x, int $y, string|ColorInterface $color): self;

    /**
     * Draw a rectangle on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-rectangle
     */
    public function drawRectangle(callable|Rectangle $rectangle, ?callable $adjustments = null): self;

    /**
     * Draw an ellipse on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-ellipses
     */
    public function drawEllipse(callable|Ellipse $ellipse, ?callable $adjustments = null): self;

    /**
     * Draw a circle on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-circle
     */
    public function drawCircle(callable|Circle $circle, ?callable $adjustments = null): self;

    /**
     * Draw a polygon on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-polygon
     */
    public function drawPolygon(callable|Polygon $polygon, ?callable $adjustments = null): self;

    /**
     * Draw a line on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-line
     */
    public function drawLine(callable|Line $line, ?callable $adjustments = null): self;

    /**
     * Draw a bezier curve on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-bezier-curves
     */
    public function drawBezier(callable|Bezier $bezier, ?callable $adjustments = null): self;

    /**
     * Draw a geometric object on the image.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing
     */
    public function draw(DrawableInterface $drawable, ?callable $adjustments = null): self;

    /**
     * Encode the image with the given encoder. If no encoder is provided,
     * the format is detected from the original image automatically.
     */
    public function encode(?EncoderInterface $encoder = null): EncodedImageInterface;

    /**
     * Encode the image in the given format.
     */
    public function encodeUsingFormat(Format $format, mixed ...$options): EncodedImageInterface;

    /**
     * Encode the image based on the given media (MIME) type.
     */
    public function encodeUsingMediaType(string|MediaType $mediaType, mixed ...$options): EncodedImageInterface;

    /**
     * Encode the image based on the given file extension.
     */
    public function encodeUsingFileExtension(
        string|FileExtension $fileExtension,
        mixed ...$options,
    ): EncodedImageInterface;

    /**
     * Encode the image based on the given file path's extension.
     */
    public function encodeUsingPath(string $path, mixed ...$options): EncodedImageInterface;
}
