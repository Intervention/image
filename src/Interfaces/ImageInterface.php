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
    public function colorspace(): ColorspaceInterface;
}
