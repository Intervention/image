<?php

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\EncodedImage;
use IteratorAggregate;

interface ImageInterface extends IteratorAggregate, Countable
{
    public function driver(): DriverInterface;
    public function core(): CoreInterface;
    public function width(): int;
    public function height(): int;
    public function size(): SizeInterface;
    public function encode(EncoderInterface $encoder): EncodedImage;
    public function modify(ModifierInterface $modifier): ImageInterface;
    public function analyze(AnalyzerInterface $analyzer): mixed;
    public function isAnimated(): bool;
    public function loops(): int;
    public function exif(?string $query = null): mixed;
    public function resolution(): ResolutionInterface;
    public function colorspace(): ColorspaceInterface;
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface;
    public function pickColors(int $x, int $y): CollectionInterface;
    public function profile(): ProfileInterface;
    public function sharpen(int $amount = 10): ImageInterface;
    public function greyscale(): ImageInterface;
    public function pixelate(int $size): ImageInterface;
    public function text(string $text, int $x, int $y, callable|FontInterface $font): ImageInterface;
}
