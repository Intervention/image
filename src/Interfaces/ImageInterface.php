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
     * @param int $key
     * @return null|FrameInterface
     */
    public function getFrame(int $position = 0): ?FrameInterface;

    /**
     * Add frame to animated image
     *
     * @param FrameInterface $frame
     * @return ImageInterface
     */
    public function addFrame(FrameInterface $frame): ImageInterface;

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
    public function getSize(): SizeInterface;

    /**
     * Determine if current image is animated
     *
     * @return bool
     */
    public function isAnimated(): bool;


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
     * Encode image to png format
     *
     * @return EncodedImage
     */
    public function toPng(): EncodedImage;

    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface;
    public function pickColors(int $x, int $y): CollectionInterface;
    public function text(string $text, int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Turn image into a greyscale version
     *
     * @return void
     */
    public function greyscale(): ImageInterface;


    /**
     * Blur current image by given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function blur(int $amount = 5): ImageInterface;
    public function rotate(float $angle, $background = 'ffffff'): ImageInterface;
    public function place($element, string $position = 'top-left', int $offset_x = 0, int $offset_y = 0): ImageInterface;
    public function fill($color, ?int $x = null, ?int $y = null): ImageInterface;
    public function pixelate(int $size): ImageInterface;
    public function resize(?int $width = null, ?int $height = null): ImageInterface;
    public function resizeDown(?int $width = null, ?int $height = null): ImageInterface;
    public function scale(?int $width = null, ?int $height = null): ImageInterface;
    public function scaleDown(?int $width = null, ?int $height = null): ImageInterface;
    public function fit(int $width, int $height, string $position = 'center'): ImageInterface;
    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface;
    public function pad(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface;
    public function padDown(int $width, int $height, $background = 'ffffff', string $position = 'center'): ImageInterface;
    public function drawPixel(int $x, int $y, $color = null): ImageInterface;
    public function drawRectangle(int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Draw ellipse ot given position on current image
     *
     * @param int $x
     * @param int $y
     * @param null|callable $init
     * @return ImageInterface
     */
    public function drawEllipse(int $x, int $y, ?callable $init = null): ImageInterface;

    /**
     * Draw line on image
     *
     * @param callable|null $init
     * @return ImageInterface
     */
    public function drawLine(callable $init = null): ImageInterface;

    /**
     * Draw polygon on image
     *
     * @param callable|null $init
     * @return ImageInterface
     */
    public function drawPolygon(callable $init = null): ImageInterface;

    /**
     * Sharpen the current image with given strength
     *
     * @param int $amount
     * @return ImageInterface
     */
    public function sharpen(int $amount = 10): ImageInterface;

    /**
     * Mirror the current image horizontally
     *
     * @return void
     */
    public function flip(): ImageInterface;

    /**
     * Mirror the current image vertically
     *
     * @return void
     */
    public function flop(): ImageInterface;

    /**
     * Return image width in pixels
     *
     * @return int
     */
    public function getWidth(): int;

    /**
     * Return image height in pixels
     *
     * @return int
     */
    public function getHeight(): int;

    /**
     * Destroy current image instance and free up memory
     *
     * @return void
     */
    public function destroy(): void;
}
