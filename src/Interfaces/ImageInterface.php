<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Exceptions\RuntimeException;
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
     * @link https://image.intervention.io/v3/basics/meta-information#reading-the-pixel-width
     * @throws RuntimeException
     * @return int
     */
    public function width(): int;

    /**
     * Return height of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#reading-the-pixel-height
     * @throws RuntimeException
     * @return int
     */
    public function height(): int;

    /**
     * Return size of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#reading-the-image-size-as-an-object
     * @throws RuntimeException
     * @return SizeInterface
     */
    public function size(): SizeInterface;

    /**
     * Encode image with given encoder
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-images
     * @param EncoderInterface $encoder
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function encode(EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface;

    /**
     * Save the image to the specified path in the file system. If no path is
     * given, the image will be saved at its original location.
     *
     * @link https://image.intervention.io/v3/basics/image-output#writing-images-directly
     * @param null|string $path
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function save(?string $path = null, ...$options): self;

    /**
     * Apply given modifier to current image
     *
     * @link https://image.intervention.io/v3/modifying/custom-modifiers
     * @param ModifierInterface $modifier
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function modify(ModifierInterface $modifier): self;

    /**
     * Analyzer current image with given analyzer
     *
     * @param AnalyzerInterface $analyzer
     * @throws RuntimeException
     * @return mixed
     */
    public function analyze(AnalyzerInterface $analyzer): mixed;

    /**
     * Determine if current image is animated
     *
     * @link https://image.intervention.io/v3/modifying/animations#check-the-current-image-instance-for-animation
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
     * @link https://image.intervention.io/v3/modifying/animations#removing-animation
     * @param int|string $position
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function removeAnimation(int|string $position = 0): self;

    /**
     * Extract animation frames based on given values and discard the rest
     *
     * @link https://image.intervention.io/v3/modifying/animations#changing-the-animation-frames
     * @param int $offset
     * @param null|int $length
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): self;

    /**
     * Return loop count of animated image
     *
     * @link https://image.intervention.io/v3/modifying/animations#reading-the-animation-iteration-count
     * @return int
     */
    public function loops(): int;

    /**
     * Set loop count of animated image
     *
     * @link https://image.intervention.io/v3/modifying/animations#changing-the-animation-iteration-count
     * @param int $loops
     * @return ImageInterface
     */
    public function setLoops(int $loops): self;

    /**
     * Return exif data of current image
     *
     * @link https://image.intervention.io/v3/basics/meta-information#exif-information
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
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     * @throws RuntimeException
     * @return ResolutionInterface
     */
    public function resolution(): ResolutionInterface;

    /**
     * Set image resolution
     *
     * @link https://image.intervention.io/v3/basics/meta-information#image-resolution
     * @param float $x
     * @param float $y
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function setResolution(float $x, float $y): self;

    /**
     * Get the colorspace of the image
     *
     * @link https://image.intervention.io/v3/basics/colors#reading-the-colorspace
     * @throws RuntimeException
     * @return ColorspaceInterface
     */
    public function colorspace(): ColorspaceInterface;

    /**
     * Transform image to given colorspace
     *
     * @link https://image.intervention.io/v3/basics/colors#changing-the-colorspace
     * @param string|ColorspaceInterface $colorspace
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Return color of pixel at given position on given frame position
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     * @param int $x
     * @param int $y
     * @param int $frame_key
     * @throws RuntimeException
     * @return ColorInterface
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface;

    /**
     * Return all colors of pixel at given position for all frames of image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-information
     * @param int $x
     * @param int $y
     * @throws RuntimeException
     * @return CollectionInterface
     */
    public function pickColors(int $x, int $y): CollectionInterface;

    /**
     * Return color that is mixed with transparent areas when converting to a format which
     * does not support transparency.
     *
     * @link https://image.intervention.io/v3/basics/colors#transparency
     * @return ColorInterface
     */
    public function blendingColor(): ColorInterface;

    /**
     * Set blending color will have no effect unless image is converted into a format
     * which does not support transparency.
     *
     * @link https://image.intervention.io/v3/basics/colors#transparency
     * @param mixed $color
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function setBlendingColor(mixed $color): self;

    /**
     * Replace transparent areas of the image with given color
     *
     * @param mixed $color
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function blendTransparency(mixed $color = null): self;

    /**
     * Retrieve ICC color profile of image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     * @throws RuntimeException
     * @return ProfileInterface
     */
    public function profile(): ProfileInterface;

    /**
     * Set given icc color profile to image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     * @param ProfileInterface $profile
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function setProfile(ProfileInterface $profile): self;

    /**
     * Remove ICC color profile from the current image
     *
     * @link https://image.intervention.io/v3/basics/colors#color-profiles
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function removeProfile(): self;

    /**
     * Apply color quantization to the current image
     *
     * @link https://image.intervention.io/v3/modifying/effects#reduce-colors
     * @param int $limit
     * @param mixed $background
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function reduceColors(int $limit, mixed $background = 'transparent'): self;

    /**
     * Sharpen the current image with given strength
     *
     * @link https://image.intervention.io/v3/modifying/effects#sharpening-effect
     * @param int $amount
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function sharpen(int $amount = 10): self;

    /**
     * Turn image into a greyscale version
     *
     * @link https://image.intervention.io/v3/modifying/effects#convert-image-to-a-greyscale-version
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function greyscale(): self;

    /**
     * Adjust brightness of the current image
     *
     * @param int $level
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function brightness(int $level): self;

    /**
     * Adjust color contrast of the current image
     *
     * @link https://image.intervention.io/v3/modifying/effects#changing-the-brightness
     * @param int $level
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function contrast(int $level): self;

    /**
     * Apply gamma correction on the current image
     *
     * @link https://image.intervention.io/v3/modifying/effects#gamma-correction
     * @param float $gamma
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function gamma(float $gamma): self;

    /**
     * Adjust the intensity of the RGB color channels
     *
     * @link https://image.intervention.io/v3/modifying/effects#color-correction
     * @param int $red
     * @param int $green
     * @param int $blue
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): self;

    /**
     * Mirror the current image horizontally
     *
     * @link https://image.intervention.io/v3/modifying/effects#mirror-image-vertically
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function flip(): self;

    /**
     * Mirror the current image vertically
     *
     * @link https://image.intervention.io/v3/modifying/effects#mirror-image-horizontally
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function flop(): self;

    /**
     * Blur current image by given strength
     *
     * @link https://image.intervention.io/v3/modifying/effects#blur-effect
     * @param int $amount
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function blur(int $amount = 5): self;

    /**
     * Invert the colors of the current image
     *
     * @link https://image.intervention.io/v3/modifying/effects#invert-colors
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function invert(): self;

    /**
     * Apply pixelation filter effect on current image
     *
     * @link https://image.intervention.io/v3/modifying/effects#pixelation-effect
     * @param int $size
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function pixelate(int $size): self;

    /**
     * Rotate current image by given angle
     *
     * @link https://image.intervention.io/v3/modifying/effects#image-rotation
     * @param float $angle
     * @param string $background
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): self;

    /**
     * Draw text on image
     *
     * @ink https://image.intervention.io/v3/modifying/text-fonts
     * @param string $text
     * @param int $x
     * @param int $y
     * @param callable|FontInterface $font
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function text(string $text, int $x, int $y, callable|FontInterface $font): self;

    /**
     * Resize image to the given width and/or height
     *
     * @link https://image.intervention.io/v3/modifying/resizing#simple-image-resizing
     * @param null|int $width
     * @param null|int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function resize(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height without exceeding the original dimensions
     *
     * @link https://image.intervention.io/v3/modifying/resizing#resizing-without-exceeding-the-original-size
     * @param null|int $width
     * @param null|int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function resizeDown(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height and keep the original aspect ratio
     *
     * @link https://image.intervention.io/v3/modifying/resizing#scaling-images
     * @param null|int $width
     * @param null|int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function scale(?int $width = null, ?int $height = null): self;

    /**
     * Resize image to the given width and/or height, keep the original aspect ratio
     * and do not exceed the original image width or height
     *
     * @link https://image.intervention.io/v3/modifying/resizing#scaling-images-but-do-not-exceed-the-original-size
     * @param null|int $width
     * @param null|int $height
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function scaleDown(?int $width = null, ?int $height = null): self;

    /**
     * Takes the given dimensions and scales it to the largest possible size matching
     * the original size. Then this size is positioned on the original and cut out
     * before being resized to the desired size from the arguments
     *
     * @link https://image.intervention.io/v3/modifying/resizing#fitted-image-resizing
     * @param int $width
     * @param int $height
     * @param string $position
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function cover(int $width, int $height, string $position = 'center'): self;

    /**
     * Same as cover() but do not exceed the original image size
     *
     * @link https://image.intervention.io/v3/modifying/resizing#fitted-resizing-without-exceeding-the-original-size
     * @param int $width
     * @param int $height
     * @param string $position
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function coverDown(int $width, int $height, string $position = 'center'): self;

    /**
     * Resize the boundaries of the current image to given width and height.
     * An anchor position can be defined to determine where the original image
     * is fixed. A background color can be passed to define the color of the
     * new emerging areas.
     *
     * @link https://image.intervention.io/v3/modifying/resizing#resize-image-canvas
     * @param null|int $width
     * @param null|int $height
     * @param string $position
     * @param mixed $background
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/resizing#resize-image-boundaries-relative-to-the-original
     * @param null|int $width
     * @param null|int $height
     * @param string $position
     * @param mixed $background
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/resizing#padded-image-resizing
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/resizing#padded-resizing-with-upscaling
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/resizing#crop-image
     * @param int $width
     * @param int $height
     * @param int $offset_x
     * @param int $offset_y
     * @param mixed $background
     * @param string $position
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/inserting
     * @param mixed $element
     * @param string $position
     * @param int $offset_x
     * @param int $offset_y
     * @param int $opacity
     * @throws RuntimeException
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
     * @link https://image.intervention.io/v3/modifying/drawing#fill-images-with-color
     * @param mixed $color
     * @param null|int $x
     * @param null|int $y
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): self;

    /**
     * Draw a single pixel at given position defined by the coordinates x and y in a given color.
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-a-pixel
     * @param int $x
     * @param int $y
     * @param mixed $color
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawPixel(int $x, int $y, mixed $color): self;

    /**
     * Draw a rectangle on the current image
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-a-rectangle
     * @param int $x
     * @param int $y
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawRectangle(int $x, int $y, callable $init): self;

    /**
     * Draw ellipse on the current image
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-ellipses
     * @param int $x
     * @param int $y
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawEllipse(int $x, int $y, callable $init): self;

    /**
     * Draw circle on the current image
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-a-circle
     * @param int $x
     * @param int $y
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawCircle(int $x, int $y, callable $init): self;

    /**
     * Draw a polygon on the current image
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-a-polygon
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawPolygon(callable $init): self;

    /**
     * Draw a line on the current image
     *
     * @link https://image.intervention.io/v3/modifying/drawing#drawing-a-line
     * @param callable $init
     * @throws RuntimeException
     * @return ImageInterface
     */
    public function drawLine(callable $init): self;

    /**
     * Encode image to given media (mime) type. If no type is given the image
     * will be encoded to the format of the originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-media-mime-type
     * @param null|string $type
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function encodeByMediaType(?string $type = null, ...$options): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension. If no
     * extension is given the image will be encoded to the format of the
     * originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-file-extension
     * @param null|string $extension
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function encodeByExtension(?string $extension = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode the image into the format represented by the given extension of
     * the given file path extension is given the image will be encoded to
     * the format of the originally read image.
     *
     * @link https://image.intervention.io/v3/basics/image-output#encode-images-by-file-path
     * @param null|string $path
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function encodeByPath(?string $path = null, mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to JPEG format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-jpeg-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */

    public function toJpeg(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Jpeg2000 format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-jpeg-2000-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toJpeg2000(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Webp format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-webp-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toWebp(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to PNG format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-png-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toPng(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to GIF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-gif-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toGif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to Bitmap format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-windows-bitmap-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toBitmap(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to AVIF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-av1-image-file-format-avif
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toAvif(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to TIFF format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-tiff-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toTiff(mixed ...$options): EncodedImageInterface;

    /**
     * Encode image to HEIC format
     *
     * @link https://image.intervention.io/v3/basics/image-output#encoding-heic-format
     * @param mixed $options
     * @throws RuntimeException
     * @return EncodedImageInterface
     */
    public function toHeic(mixed ...$options): EncodedImageInterface;
}
