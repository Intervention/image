<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Origin;
use IteratorAggregate;

interface ImageInterface extends IteratorAggregate, Countable
{
    /**
     * Return driver of current image
     *
     * @return DriverInterface
     */
    public function driver(): DriverInterface;

    /**
     * Return core of current image
     *
     * @return CoreInterface
     */
    public function core(): CoreInterface;

    /**
     * Return the origin of the image
     *
     * @return Origin
     */
    public function origin(): Origin;

    /**
     * Set the origin of the image
     *
     * @param Origin $origin
     * @return ImageInterface
     */
    public function setOrigin(Origin $origin): self;

    /**
     * Return width of current image
     *
     * @return int
     */
    public function width(): int;

    /**
     * Return height of current image
     *
     * @return int
     */
    public function height(): int;

    /**
     * Return size of current image
     *
     * @return SizeInterface
     */
    public function size(): SizeInterface;

    /**
     * Encode image with given encoder
     *
     * @param EncoderInterface $encoder
     * @return EncodedImageInterface
     */
    public function encode(EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface;

    /**
     * Save the image to the specified path in the file system. If no path is
     * given, the image will be saved at its original location.
     *
     * @param null|string $path
     * @return ImageInterface
     */
    public function save(?string $path = null, ...$options): self;

    /**
     * Apply given modifier to current image
     *
     * @param ModifierInterface $modifier
     * @return ImageInterface
     */
    public function modify(ModifierInterface $modifier): self;

    /**
     * Analyzer current image with given analyzer
     *
     * @param AnalyzerInterface $analyzer
     * @return mixed
     */
    public function analyze(AnalyzerInterface $analyzer): mixed;

    /**
     * Determine if current image is animated
     *
     * @return bool
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
     * @param int|string $position
     * @return ImageInterface
     */
    public function removeAnimation(int|string $position = 0): self;

    /**
     * Extract animation frames based on given values and discard the rest
     *
     * @param int $offset
     * @param null|int $length
     * @return ImageInterface
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): self;

    /**
     * Return loop count of animated image
     *
     * @return int
     */
    public function loops(): int;

    /**
     * Set loop count of animated image
     *
     * @param int $loops
     * @return ImageInterface
     */
    public function setLoops(int $loops): self;

    /**
     * Return exif data of current image
     *
     * @return mixed
     */
    public function exif(?string $query = null): mixed;

    /**
     * Set exif data for the image object
     *
     * @param CollectionInterface $exif
     * @return ImageInterface
     */
    public function setExif(CollectionInterface $exif): self;

    /**
     * Return image resolution/density
     *
     * @return ResolutionInterface
     */
    public function resolution(): ResolutionInterface;

    /**
     * Set image resolution
     *
     * @param float $x
     * @param float $y
     * @return ImageInterface
     */
    public function setResolution(float $x, float $y): self;

    /**
     * Get the colorspace of the image
     *
     * @return ColorspaceInterface
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Transform image to given colorspace
     *
     * @param string|ColorspaceInterface $colorspace
     * @return ImageInterface
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Return color of pixel at given position on given frame position
     *
     * @param int $x
     * @param int $y
     * @param int $frame_key
     * @return ColorInterface
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface;

    /**
     * Return all colors of pixel at given position for all frames of image
     *
     * @param int $x
     * @param int $y
     * @return CollectionInterface
     */
    public function pickColors(int $x, int $y): CollectionInterface;

    /**
     * Return color that is mixed with transparent areas when converting to a format which
     * does not support transparency.
     *
     * @return ColorInterface
     */
    public function blendingColor(): ColorInterface;

    /**
     * Set blending color will have no effect unless image is converted into a format
     * which does not support transparency.
     *
     * @param mixed $color
     * @return ImageInterface
     */
    public function setBlendingColor(mixed $color): self;

    /**
     * Replace transparent areas of the image with given color
     *
     * @param mixed $color
     * @return ImageInterface
     */
    public function blendTransparency(mixed $color = null): self;

    /**
     * Retrieve ICC color profile of image
     *
     * @return ProfileInterface
     */
    public function profile(): ProfileInterface;

    /**
     * Set given icc color profile to image
     *
     * @param ProfileInterface $profile
     * @return ImageInterface
     */
    public function setProfile(ProfileInterface $profile): self;

    /**
     * Remove ICC color profile from the current image
     *
     * @return ImageInterface
     */
    public function removeProfile(): self;

    /**
     * Apply color quantization to the current image
     *
     * @param int $limit
     * @param mixed $background
     * @return ImageInterface
     */
    public function reduceColors(int $limit, mixed $background = 'transparent'): self;

    /**
     * Sharpen the current image with given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function sharpen(int $amount = 10): self;

    /**
     * Turn image into a greyscale version
     *
     * @return ImageInterface
     */
    public function greyscale(): self;

    /**
     * Adjust brightness of the current image
     *
     * @param int $level
     * @return ImageInterface
     */
    public function brightness(int $level): self;

    /**
     * Adjust color contrast of the current image
     *
     * @param int $level
     * @return ImageInterface
     */
    public function contrast(int $level): self;

    /**
     * Apply gamma correction on the current image
     *
     * @param float $gamma
     * @return ImageInterface
     */
    public function gamma(float $gamma): self;

    /**
     * Adjust the intensity of the RGB color channels
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return ImageInterface
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): self;

    /**
     * Mirror the current image horizontally
     *
     * @return ImageInterface
     */
    public function flip(): self;

    /**
     * Mirror the current image vertically
     *
     * @return ImageInterface
     */
    public function flop(): self;

    /**
     * Blur current image by given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function blur(int $amount = 5): self;

    /**
     * Invert the colors of the current image
     *
     * @return ImageInterface
     */
    public function invert(): self;

    /**
     * Apply pixelation filter effect on current image
     *
     * @param int $size
     * @return ImageInterface
     */
    public function pixelate(int $size): self;

    /**
     * Rotate current image by given angle
     *
     * @param float $angle
     * @param string $background
     * @return ImageInterface
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): self;

    /**
     * Draw text on image
     *
     * @param string $text
     * @param int $x
     * @param int $y
     * @param callable|FontInterface $font
     * @return ImageInterface
     */
    public function text(string $text, int $x, int $y, callable|FontInterface $font): self;

    /**
     * Resize image to the given width and/or height
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resize(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height without exceeding the original dimensions
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resizeDown(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height and keep the original aspect ratio
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scale(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height, keep the original aspect ratio
     * and do not exceed the original image width or height
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scaleDown(?int $width = null, ?int $height = null): self;

    /**
     * Takes the given dimensions and scales it to the largest possible size matching
     * the original size. Then this size is positioned on the original and cut out
     * before being resized to the desired size from the arguments
     *
     * @param int $width
     * @param int $height
     * @param string $position
     * @return ImageInterface
     */
    public function cover(int $width, int $height, string $position = 'center'): self;

    /**
     * Same as cover() but do not exceed the original image size
     *
     * @param int $width
     * @param int $height
     * @param string $position
     * @return ImageInterface
     */
    public function coverDown(int $width, int $height, string $position = 'center'): self;

    /**
     * Resize the boundaries of the current image to given width and height.
     * An anchor position can be defined to determine where the original image
     * is fixed. A background color can be passed to define the color of the
     * new emerging areas.
     *
     * @param null|int $width
     * @param null|int $height
     * @param string $position
     * @param mixed $background
     * @return ImageInterface
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
     * @param null|int $width
     * @param null|int $height
     * @param string $position
     * @param mixed $background
     * @return ImageInterface
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
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @return ImageInterface
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
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @return ImageInterface
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
     * @param int $width
     * @param int $height
     * @param int $offset_x
     * @param int $offset_y
     * @param mixed $background
     * @param string $position
     * @return ImageInterface
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
     * Place another image into the current image instance
     *
     * @param mixed $element
     * @param string $position
     * @param int $offset_x
     * @param int $offset_y
     * @param int $opacity
     * @return ImageInterface
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
     * If coordinates are transferred in the form of X and Y values, the function
     * is executed as a flood fill. This means that the color at the specified
     * position is taken as a reference and all adjacent pixels are also filled
     * with the same color.
     *
     * If no coordinates are specified, the entire image area is filled.
     *
     * @param mixed $color
     * @param null|int $x
     * @param null|int $y
     * @return ImageInterface
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): self;

    /**
     * Draw a single pixel at given position defined by the coordinates x and y in a given color.
     *
     * @param int $x
     * @param int $y
     * @param mixed $color
     * @return ImageInterface
     */
    public function drawPixel(int $x, int $y, mixed $color): self;

    /**
     * Draw a rectangle on the current image
     *
     * @param int $x
     * @param int $y
     * @param callable $init
     * @return ImageInterface
     */
    public function drawRectangle(int $x, int $y, callable $init): self;

    /**
     * Draw ellipse on the current image
     *
     * @param int $x
     * @param int $y
     * @param callable $init
     * @return ImageInterface
     */
    public function drawEllipse(int $x, int $y, callable $init): self;

    /**
     * Draw circle on the current image
     *
     * @param int $x
     * @param int $y
     * @param callable $init
     * @return ImageInterface
     */
    public function drawCircle(int $x, int $y, callable $init): self;

    /**
     * Draw a polygon on the current image
     *
     * @param callable $init
     * @return ImageInterface
     */
    public function drawPolygon(callable $init): self;

    /**
     * Draw a line on the current image
     *
     * @param callable $init
     * @return ImageInterface
     */
    public function drawLine(callable $init): self;

    /**
     * Encode image to given media (mime) type. If no type is given the image
     * will be encoded to the format of the originally read image.
     *
     * @param null|string $type
     * @return EncodedImageInterface
     */
    public function encodeByMediaType(?string $type = null, ...$options): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension. If no
     * extension is given the image will be encoded to the format of the
     * originally read image.
     *
     * @param null|string $extension
     * @return EncodedImageInterface
     */
    public function encodeByExtension(?string $extension = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension of
     * the given file path extension is given the image will be encoded to
     * the format of the originally read image.
     *
     * @param null|string $path
     * @return EncodedImageInterface
     */
    public function encodeByPath(?string $path = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to JPEG format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */

    public function toJpeg(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Jpeg2000 format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toJpeg2000(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Webp format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toWebp(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to PNG format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toPng(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to GIF format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toGif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Bitmap format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toBitmap(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to AVIF format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toAvif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to TIFF format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toTiff(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to HEIC format
     *
     * @param mixed $options
     * @return EncodedImageInterface
     */
    public function toHeic(mixed ...$options): EncodedImageInterface;
}
