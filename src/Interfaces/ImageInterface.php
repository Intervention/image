<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Closure;
use Countable;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\FileExtension;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\MediaType;
use Intervention\Image\Origin;
use IteratorAggregate;

/**
 * @extends IteratorAggregate<FrameInterface>
 */
interface ImageInterface extends IteratorAggregate, Countable
{
    /**
     * Return driver of current image
     */
    public function driver(): DriverInterface;

    /**
     * Return core of current image
     */
    public function core(): CoreInterface;

    /**
     * Return the origin of the image
     */
    public function origin(): Origin;

    /**
     * Set the origin of the image
     */
    public function setOrigin(Origin $origin): self;

    /**
     * Return width of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-width
     *
     * @throws RuntimeException
     */
    public function width(): int;

    /**
     * Return height of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-pixel-height
     *
     * @throws RuntimeException
     */
    public function height(): int;

    /**
     * Return size of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#read-the-image-size-as-an-object
     *
     * @throws RuntimeException
     */
    public function size(): SizeInterface;

    /**
     * Encode image with given encoder
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images
     *
     * @throws RuntimeException
     */
    public function encode(EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface;

    /**
     * Save the image to the specified path in the file system. If no path is
     * given, the image will be saved at its original location.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode--save-combined
     *
     * @throws RuntimeException
     */
    public function save(?string $path = null, mixed ...$options): self;

    /**
     * Apply given modifier to current image
     *
     * @link https://image.intervention.io/v3/modifying-images/custom-modifiers
     *
     * @throws RuntimeException
     */
    public function modify(ModifierInterface $modifier): self;

    /**
     * Analyzer current image with given analyzer
     *
     * @throws RuntimeException
     */
    public function analyze(AnalyzerInterface $analyzer): mixed;

    /**
     * Determine if current image is animated
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#check-the-current-image-instance-for-animation
     */
    public function isAnimated(): bool;

    /**
     * Remove all frames but keep the one at the specified position
     *
     * It is possible to specify the position as integer or string values.
     * With the former, the exact position passed is searched for, while
     * string values must represent a percentage value between '0%' and '100%'
     * and the respective frame position is only determined approximately.
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#remove-animation
     *
     * @throws RuntimeException
     */
    public function removeAnimation(int|string $position = 0): self;

    /**
     * Extract animation frames based on given values and discard the rest
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     *
     * @throws RuntimeException
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): self;

    /**
     * Return loop count of animated image
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#read-the-animation-iteration-count
     */
    public function loops(): int;

    /**
     * Set loop count of animated image
     *
     * @link https://image.intervention.io/v3/modifying-images/animations#change-the-animation-iteration-count
     */
    public function setLoops(int $loops): self;

    /**
     * Return exif data of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#exif-information
     */
    public function exif(?string $query = null): mixed;

    /**
     * Set exif data for the image object
     */
    public function setExif(CollectionInterface $exif): self;

    /**
     * Return image resolution/density
     *
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     *
     * @throws RuntimeException
     */
    public function resolution(): ResolutionInterface;

    /**
     * Set image resolution
     *
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     *
     * @throws RuntimeException
     */
    public function setResolution(float $x, float $y): self;

    /**
     * Get the colorspace of the image
     *
     * @link https://image.intervention.io/v3/basics/colors#read-the-image-colorspace
     *
     * @throws RuntimeException
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Transform image to given colorspace
     *
     * @link https://image.intervention.io/v3/basics/colors#change-the-image-colorspace
     *
     * @throws RuntimeException
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Return color of pixel at given position on given frame position
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     *
     * @throws RuntimeException
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface;

    /**
     * Return all colors of pixel at given position for all frames of image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     *
     * @throws RuntimeException
     */
    public function pickColors(int $x, int $y): CollectionInterface;

    /**
     * Return color that is mixed with transparent areas when converting to a format which
     * does not support transparency.
     *
     * @throws RuntimeException
     */
    public function blendingColor(): ColorInterface;

    /**
     * Set blending color will have no effect unless image is converted into a format
     * which does not support transparency.
     *
     * @throws RuntimeException
     */
    public function setBlendingColor(mixed $color): self;

    /**
     * Replace transparent areas of the image with given color
     *
     * @throws RuntimeException
     */
    public function blendTransparency(mixed $color = null): self;

    /**
     * Retrieve ICC color profile of image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     *
     * @throws RuntimeException
     */
    public function profile(): ProfileInterface;

    /**
     * Set given icc color profile to image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     *
     * @throws RuntimeException
     */
    public function setProfile(ProfileInterface $profile): self;

    /**
     * Remove ICC color profile from the current image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     *
     * @throws RuntimeException
     */
    public function removeProfile(): self;

    /**
     * Apply color quantization to the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#reduce-colors
     *
     * @throws RuntimeException
     */
    public function reduceColors(int $limit, mixed $background = 'transparent'): self;

    /**
     * Sharpen the current image with given strength
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#sharpening-effect
     *
     * @throws RuntimeException
     */
    public function sharpen(int $amount = 10): self;

    /**
     * Turn image into a greyscale version
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#convert-image-to-a-greyscale-version
     *
     * @throws RuntimeException
     */
    public function greyscale(): self;

    /**
     * Adjust brightness of the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-brightness
     *
     * @throws RuntimeException
     */
    public function brightness(int $level): self;

    /**
     * Adjust color contrast of the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#change-the-image-contrast
     *
     * @throws RuntimeException
     */
    public function contrast(int $level): self;

    /**
     * Apply gamma correction on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#gamma-correction
     *
     * @throws RuntimeException
     */
    public function gamma(float $gamma): self;

    /**
     * Adjust the intensity of the RGB color channels
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#color-correction
     *
     * @throws RuntimeException
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): self;

    /**
     * Mirror the current image vertically by swapping top and bottom
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#mirror-image-vertically
     *
     * @throws RuntimeException
     */
    public function flip(): self;

    /**
     * Mirror the current image horizontally by swapping left and right
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#mirror-image-horizontally
     *
     * @throws RuntimeException
     */
    public function flop(): self;

    /**
     * Blur current image by given strength
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#blur-effect
     *
     * @throws RuntimeException
     */
    public function blur(int $amount = 5): self;

    /**
     * Invert the colors of the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#invert-colors
     *
     * @throws RuntimeException
     */
    public function invert(): self;

    /**
     * Apply pixelation filter effect on current image
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#pixelation-effect
     *
     * @throws RuntimeException
     */
    public function pixelate(int $size): self;

    /**
     * Rotate current image by given angle
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-rotation
     *
     * @param string $background
     * @throws RuntimeException
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): self;

    /**
     * Rotate the image to be upright according to exif information
     *
     * @link https://image.intervention.io/v3/modifying-images/effects#image-orientation-according-to-exif-data
     *
     * @throws RuntimeException
     */
    public function orient(): self;

    /**
     * Draw text on image
     *
     * @link https://image.intervention.io/v3/modifying-images/text-fonts
     *
     * @throws RuntimeException
     */
    public function text(string $text, int $x, int $y, callable|Closure|FontInterface $font): self;

    /**
     * Resize image to the given width and/or height
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#simple-image-resizing
     *
     * @throws RuntimeException
     */
    public function resize(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height without exceeding the original dimensions
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-without-exceeding-the-original-size
     *
     * @throws RuntimeException
     */
    public function resizeDown(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height and keep the original aspect ratio
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-images-proportionally
     *
     * @throws RuntimeException
     */
    public function scale(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height, keep the original aspect ratio
     * and do not exceed the original image width or height
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#scale-images-but-do-not-exceed-the-original-size
     *
     * @throws RuntimeException
     */
    public function scaleDown(?int $width = null, ?int $height = null): self;

    /**
     * Takes the specified width and height and scales them to the largest
     * possible size that fits within the original size. This scaled size is
     * then positioned on the original and cropped, before this result is resized
     * to the desired size using the arguments
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cropping--resizing-combined
     *
     * @throws RuntimeException
     */
    public function cover(int $width, int $height, string $position = 'center'): self;

    /**
     * Same as cover() but do not exceed the original image size
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#fitted-resizing-without-exceeding-the-original-size
     *
     * @throws RuntimeException
     */
    public function coverDown(int $width, int $height, string $position = 'center'): self;

    /**
     * Resize the boundaries of the current image to given width and height.
     * An anchor position can be defined to determine where the original image
     * is fixed. A background color can be passed to define the color of the
     * new emerging areas.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-without-resampling-the-original-image
     *
     * @throws RuntimeException
     */
    public function resizeCanvas(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): self;

    /**
     * Resize canvas in the same way as resizeCanvas() but takes relative values
     * for the width and height, which will be added or subtracted to the
     * original image size.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#resize-image-boundaries-relative-to-the-original
     *
     * @throws RuntimeException
     */
    public function resizeCanvasRelative(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
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
     * @throws RuntimeException
     */
    public function pad(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): self;

    /**
     * This method does the same as pad(), but the original image is also scaled
     * up if the target size exceeds the original size.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#padded-resizing-with-upscaling
     *
     * @param string $background
     * @throws RuntimeException
     */
    public function contain(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): self;

    /**
     * Cut out a rectangular part of the current image with given width and
     * height at a given position. Define optional x,y offset coordinates
     * to move the cutout by the given amount of pixels.
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#cut-out-a-rectangular-part
     *
     * @throws RuntimeException
     */
    public function crop(
        int $width,
        int $height,
        int $offset_x = 0,
        int $offset_y = 0,
        mixed $background = 'ffffff',
        string $position = 'top-left'
    ): self;

    /**
     * Trim the image by removing border areas of similar color within a the given tolerance
     *
     * @link https://image.intervention.io/v3/modifying-images/resizing#remove-border-areas-in-similar-color
     *
     * @throws RuntimeException
     * @throws AnimationException
     */
    public function trim(int $tolerance = 0): self;

    /**
     * Place another image into the current image instance
     *
     * @link https://image.intervention.io/v3/modifying-images/inserting#insert-images
     *
     * @throws RuntimeException
     */
    public function place(
        mixed $element,
        string $position = 'top-left',
        int $offset_x = 0,
        int $offset_y = 0,
        int $opacity = 100
    ): self;

    /**
     * Fill image with given color
     *
     * If an optional position is specified for the filling process ln the form
     * of x and y coordinates, the process is executed as flood fill. This means
     * that the color at the specified position is taken as a reference and all
     * adjacent pixels are also filled with the filling color.
     *
     * If no coordinates are specified, the entire image area is filled.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#fill-images-with-color
     *
     * @throws RuntimeException
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): self;

    /**
     * Draw a single pixel at given position defined by the coordinates x and y in a given color.
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-pixels
     *
     * @throws RuntimeException
     */
    public function drawPixel(int $x, int $y, mixed $color): self;

    /**
     * Draw a rectangle on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-rectangle
     *
     * @throws RuntimeException
     */
    public function drawRectangle(int $x, int $y, callable|Closure|Rectangle $init): self;

    /**
     * Draw ellipse on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-ellipses
     *
     * @throws RuntimeException
     */
    public function drawEllipse(int $x, int $y, callable|Closure|Ellipse $init): self;

    /**
     * Draw circle on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-circle
     *
     * @throws RuntimeException
     */
    public function drawCircle(int $x, int $y, callable|Closure|Circle $init): self;

    /**
     * Draw a polygon on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-polygon
     *
     * @throws RuntimeException
     */
    public function drawPolygon(callable|Closure|Polygon $init): self;

    /**
     * Draw a line on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-a-line
     *
     * @throws RuntimeException
     */
    public function drawLine(callable|Closure|Line $init): self;

    /**
     * Draw a bezier curve on the current image
     *
     * @link https://image.intervention.io/v3/modifying-images/drawing#draw-bezier-curves
     *
     * @throws RuntimeException
     */
    public function drawBezier(callable|Closure|Bezier $init): self;

    /**
     * Encode image to given media (mime) type. If no type is given the image
     * will be encoded to the format of the originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-media-mime-type
     *
     * @throws RuntimeException
     */
    public function encodeByMediaType(null|string|MediaType $type = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension. If no
     * extension is given the image will be encoded to the format of the
     * originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-file-extension
     *
     * @throws RuntimeException
     */
    public function encodeByExtension(
        null|string|FileExtension $extension = null,
        mixed ...$options
    ): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension of
     * the given file path extension is given the image will be encoded to
     * the format of the originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-file-path
     *
     * @throws RuntimeException
     */
    public function encodeByPath(?string $path = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to JPEG format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-jpeg-format
     *
     * @throws RuntimeException
     */
    public function toJpeg(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Jpeg2000 format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-jpeg-2000-format
     *
     * @throws RuntimeException
     */
    public function toJpeg2000(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Webp format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-webp-format
     *
     * @throws RuntimeException
     */
    public function toWebp(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to PNG format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-png-format
     *
     * @throws RuntimeException
     */
    public function toPng(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to GIF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-gif-format
     *
     * @throws RuntimeException
     */
    public function toGif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Bitmap format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-windows-bitmap-format
     *
     * @throws RuntimeException
     */
    public function toBitmap(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to AVIF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-av1-image-file-format-avif
     *
     * @throws RuntimeException
     */
    public function toAvif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to TIFF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-tiff-format
     *
     * @throws RuntimeException
     */
    public function toTiff(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to HEIC format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-heic-format
     *
     * @throws RuntimeException
     */
    public function toHeic(mixed ...$options): EncodedImageInterface;
}
