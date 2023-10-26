<?php

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\EncodedImage;
use Traversable;

interface ImageInterface extends Traversable, Countable
{
    /**
     * Get frame of animation image at given position starting with zero
     *
     * @param int $position
     * @return FrameInterface
     */
    public function frame(int $position = 0): FrameInterface;

    /**
     * Add frame to animated image
     *
     * @param FrameInterface $frame
     * @return ImageInterface
     */
    public function addFrame(FrameInterface $frame): ImageInterface;


    /**
     * Apply given callback to each frame of the image
     *
     * @param callable $callback
     * @return ImageInterface
     */
    public function eachFrame(callable $callback): ImageInterface;

    /**
     * Set loop count of animated image
     *
     * @param int $count
     * @return ImageInterface
     */
    public function setLoops(int $count): ImageInterface;

    /**
     * Return loop count of animated image
     *
     * @return int
     */
    public function getLoops(): int;

    /**
     * Return size of current image
     *
     * @return SizeInterface
     */
    public function size(): SizeInterface;

    /**
     * Return exif data of current image
     *
     * @return mixed
     */
    public function getExif(?string $query = null): mixed;

    /**
     * Set exif data on current image (will not be written in final image)
     *
     * @return ImageInterface
     */
    public function setExif(array $data): ImageInterface;

    /**
     * Determine if current image is animated
     *
     * @return bool
     */
    public function isAnimated(): bool;

    /**
     * Remove all frames but keep the one at the specified position
     *
     * @param  int $position
     * @return ImageInterface
     */
    public function removeAnimation(int $position = 0): ImageInterface;

    /**
     * Apply given modifier to current image
     *
     * @param ModifierInterface $modifier
     * @return ImageInterface
     */
    public function modify(ModifierInterface $modifier): ImageInterface;

    /**
     * Encode image with given encoder
     *
     * @param EncoderInterface $encoder
     * @return EncodedImage
     */
    public function encode(EncoderInterface $encoder): EncodedImage;

    /**
     * Encode image to jpeg format
     *
     * @param int $quality
     * @return EncodedImage
     */
    public function toJpeg(int $quality = 75): EncodedImage;

    /**
     * Encode image to webp format
     *
     * @param int $quality
     * @return EncodedImage
     */
    public function toWebp(int $quality = 75): EncodedImage;

    /**
     * Encode image to gif format
     *
     * @return EncodedImage
     */
    public function toGif(): EncodedImage;

    /**
     * Encode image to avif format
     *
     * @return EncodedImage
     */
    public function toAvif(): EncodedImage;

    /**
     * Encode image to png format
     *
     * @return EncodedImage
     */
    public function toPng(): EncodedImage;

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
     * @return ColorInterface
     */
    public function pickColors(int $x, int $y): CollectionInterface;

    /**
     * Get the colorspace of the image
     *
     * @return ColorspaceInterface
     */
    public function getColorspace(): ColorspaceInterface;

    /**
     * Transform image to given colorspace
     *
     * @param string|ColorspaceInterface $target
     * @return ImageInterface
     */
    public function setColorspace(string|ColorspaceInterface $target): ImageInterface;

    /**
     * Retrieve ICC color profile of image
     *
     * @return ProfileInterface
     */
    public function profile(): ProfileInterface;

    /**
     * Set ICC color profile on the current image
     *
     * @param string|ProfileInterface $input Path to color profile or profile object
     * @return ImageInterface
     */
    public function setProfile(string|ProfileInterface $input): ImageInterface;

    /**
     * Remove ICC color profile from the current image
     *
     * @return ImageInterface
     */
    public function withoutProfile(): ImageInterface;

    /**
     * Draw text on image
     *
     * @param string        $text
     * @param int           $x
     * @param int           $y
     * @param null|callable $init
     * @return ImageInterface
     */
    public function text(string $text, int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Turn image into a greyscale version
     *
     * @return ImageInterface
     */
    public function greyscale(): ImageInterface;


    /**
     * Blur current image by given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function blur(int $amount = 5): ImageInterface;


    /**
     * Rotate current image by given angle
     *
     * @param float  $angle
     * @param string $background
     * @return       ImageInterface
     */
    public function rotate(float $angle, $background = 'ffffff'): ImageInterface;


    /**
     * Place another image into the current image instance
     *
     * @param mixed  $element
     * @param string $position
     * @param int    $offset_x
     * @param int    $offset_y
     * @return ImageInterface
     */
    public function place($element, string $position = 'top-left', int $offset_x = 0, int $offset_y = 0): ImageInterface;

    /**
     * Stretch the image to the desired size
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resize(?int $width = null, ?int $height = null): ImageInterface;

    /**
     * Stretch the image to the desired size but do not exceed the original size
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function resizeDown(?int $width = null, ?int $height = null): ImageInterface;

    /**
     * Resize the image and keep the image aspect ration proportions
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scale(?int $width = null, ?int $height = null): ImageInterface;

    /**
     * Resize the image and keep the image aspect ration proportions but do not exceed the original size
     *
     * @param null|int $width
     * @param null|int $height
     * @return ImageInterface
     */
    public function scaleDown(?int $width = null, ?int $height = null): ImageInterface;

    /**
     *
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
     * Same as fit() but do not exceeds the original image size
     *
     * @param int $width
     * @param int $height
     * @param string $position
     * @return ImageInterface
     */
    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface;

    /**
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @return ImageInterface
     */
    public function pad(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface;

    /**
     * @param int $width
     * @param int $height
     * @param string $background
     * @param string $position
     * @return ImageInterface
     */
    public function padDown(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface;

    public function fill($color, ?int $x = null, ?int $y = null): ImageInterface;
    public function pixelate(int $size): ImageInterface;
    public function drawPixel(int $x, int $y, $color = null): ImageInterface;
    public function drawRectangle(int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Draw ellipse ot given position on current image
     *
     * @param int   $x
     * @param int   $y
     * @param       null|callable $init
     * @return ImageInterface
     */
    public function drawEllipse(int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Draw line on image
     *
     * @param  callable|null $init
     * @return ImageInterface
     */
    public function drawLine(callable $init = null): ImageInterface;

    /**
     * Draw polygon on image
     *
     * @param  callable|null $init
     * @return ImageInterface
     */
    public function drawPolygon(callable $init = null): ImageInterface;

    /**
     * Sharpen the current image with given strength
     *
     * @param  int $amount
     * @return ImageInterface
     */
    public function sharpen(int $amount = 10): ImageInterface;

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
     * Return image width in pixels
     *
     * @return int
     */
    public function width(): int;

    /**
     * Return image height in pixels
     *
     * @return int
     */
    public function height(): int;

    /**
     * Destroy current image instance and free up memory
     *
     * @return void
     */
    public function destroy(): void;
}
