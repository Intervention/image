<?php

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\EncodedImage;
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
     * @return EncodedImage
     */
    public function encode(EncoderInterface $encoder): EncodedImage;

    /**
     * Apply given modifier to current image
     *
     * @param ModifierInterface $modifier
     * @return ImageInterface
     */
    public function modify(ModifierInterface $modifier): ImageInterface;

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
     * @param  int|string $position
     * @return ImageInterface
     */
    public function removeAnimation(int|string $position = 0): ImageInterface;

    /**
     * Return loop count of animated image
     *
     * @return int
     */
    public function loops(): int;

    /**
     * Return exif data of current image
     *
     * @return mixed
     */
    public function exif(?string $query = null): mixed;

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
    public function setResolution(float $x, float $y): ImageInterface;

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
    public function setColorspace(string|ColorspaceInterface $colorspace): ImageInterface;

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
     * Retrieve ICC color profile of image
     *
     * @return ProfileInterface
     */
    public function profile(): ProfileInterface;

    /**
     * Set given icc color profile to image
     *
     * @param  ProfileInterface $profile
     * @return ImageInterface
     */
    public function setProfile(ProfileInterface $profile): ImageInterface;

    /**
     * Sharpen the current image with given strength
     *
     * @param  int $amount
     * @return ImageInterface
     */
    public function sharpen(int $amount = 10): ImageInterface;

    /**
     * Turn image into a greyscale version
     *
     * @return ImageInterface
     */
    public function greyscale(): ImageInterface;

    /**
     * Adjust brightness of the current image
     *
     * @param  int $level
     * @return ImageInterface
     */
    public function brightness(int $level): ImageInterface;

    /**
     * Adjust color contrast of the current image
     *
     * @param  int $level
     * @return ImageInterface
     */
    public function contrast(int $level): ImageInterface;

    /**
     * Apply gamma correction on the current image
     *
     * @param  float $gamma
     * @return ImageInterface
     */
    public function gamma(float $gamma): ImageInterface;

    /**
     * Adjust the intensity of the RGB color channels
     *
     * @param  int $red
     * @param  int $green
     * @param  int $blue
     * @return ImageInterface
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): ImageInterface;

    /**
     * Mirror the current image horizontally
     *
     * @return ImageInterface
     */
    public function flip(): ImageInterface;

    /**
     * Mirror the current image vertically
     *
     * @return ImageInterface
     */
    public function flop(): ImageInterface;

    /**
     * Blur current image by given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function blur(int $amount = 5): ImageInterface;

    /**
     * Invert the colors of the current image
     *
     * @return ImageInterface
     */
    public function invert(): ImageInterface;

    /**
     * Apply pixelation filter effect on current image
     *
     * @param int $size
     * @return ImageInterface
     */
    public function pixelate(int $size): ImageInterface;

    /**
     * Rotate current image by given angle
     *
     * @param float  $angle
     * @param string $background
     * @return       ImageInterface
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): ImageInterface;

    /**
     * Draw text on image
     *
     * @param string                 $text
     * @param int                    $x
     * @param int                    $y
     * @param callable|FontInterface $font
     * @return ImageInterface
     */
    public function text(string $text, int $x, int $y, callable|FontInterface $font): ImageInterface;

    /**
     * Resize image to the given width and/or height
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resize(?int $width, ?int $height): ImageInterface;

    /**
     * Resize image to the given width and/or height without exceeding the original dimensions
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resizeDown(?int $width, ?int $height): ImageInterface;

    /**
     * Resize image to the given width and/or height and keep the original aspect ratio
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scale(?int $width, ?int $height): ImageInterface;

    /**
     * Resize image to the given width and/or height, keep the original aspect ratio
     * and do not exceed the original image width or height
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scaleDown(?int $width, ?int $height): ImageInterface;

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
    public function fit(int $width, int $height, string $position = 'center'): ImageInterface;

    /**
     * Same as fit() but do not exceed the original image size
     *
     * @param int $width
     * @param int $height
     * @param string $position
     * @return ImageInterface
     */
    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface;

    /**
     * Padded resizing means that the original image is scaled until it fits the
     * defined target size with unchanged aspect ratio. Compared to the fit()
     * method, this call does not create cropped areas, but new empty areas
     * on the sides of the result image. These are filled with the specified
     * background color.
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
        string $position = 'center',
    ): ImageInterface;

    /**
     * This method does the same thing as pad() but does not exceed the size of
     * the original image. You can use this if you want to prevent up-sampling.
     *
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @return ImageInterface
     */
    public function padDown(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center',
    ): ImageInterface;

    /**
     * Cut out a rectangular part of the current image with given width and height at a given position.
     * Define optional x,y offset coordinates to move the cutout by the given amount of pixels.
     *
     * @param int $width
     * @param int $height
     * @param int $offset_x
     * @param int $offset_y
     * @param string $position
     * @return ImageInterface
     */
    public function crop(
        int $width,
        int $height,
        int $offset_x = 0,
        int $offset_y = 0,
        string $position = 'top-left',
    ): ImageInterface;

    /**
     * Place another image into the current image instance
     *
     * @param mixed  $element
     * @param string $position
     * @param int    $offset_x
     * @param int    $offset_y
     * @return ImageInterface
     */
    public function place(
        mixed $element,
        string $position = 'top-left',
        int $offset_x = 0,
        int $offset_y = 0
    ): ImageInterface;

    /**
     * Encode image to JPEG format
     *
     * @param int $quality
     * @return EncodedImageInterface
     */
    public function toJpeg(int $quality = 75): EncodedImageInterface;

    /**
     * Encode image to Webp format
     *
     * @param int $quality
     * @return EncodedImageInterface
     */
    public function toWebp(int $quality = 75): EncodedImageInterface;

    /**
     * Encode image to PNG format
     *
     * @param int $color_limit
     * @return EncodedImageInterface
     */
    public function toPng(int $color_limit = 0): EncodedImageInterface;

    /**
     * Encode image to GIF format
     *
     * @param int $color_limit
     * @return EncodedImageInterface
     */
    public function toGif(int $color_limit = 0): EncodedImageInterface;

    /**
     * Encode image to Bitmap format
     *
     * @param int $color_limit
     * @return EncodedImageInterface
     */
    public function toBitmap(int $color_limit = 0): EncodedImageInterface;

    /**
     * Encode image to AVIF format
     *
     * @param int $quality
     * @return EncodedImageInterface
     */
    public function toAvif(int $quality = 75): EncodedImageInterface;
}
