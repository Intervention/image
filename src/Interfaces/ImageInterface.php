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
     * Get the colorspace of the image
     *
     * @return ColorspaceInterface
     */
    public function colorspace(): ColorspaceInterface;

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
}
