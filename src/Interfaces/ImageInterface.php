<?php

namespace Intervention\Image\Interfaces;

use Countable;
use Intervention\Image\EncodedImage;
use Traversable;

interface ImageInterface extends Traversable, Countable
{
    public function getFrame(int $key = 0): ?FrameInterface;
    public function addFrame(FrameInterface $frame): ImageInterface;
    public function setLoops(int $count): ImageInterface;
    public function getLoops(): int;
    public function getSize(): SizeInterface;
    public function isAnimated(): bool;
    public function modify(ModifierInterface $modifier): ImageInterface;
    public function encode(EncoderInterface $encoder): EncodedImage;
    public function toJpeg(int $quality = 75): EncodedImage;
    public function toWebp(int $quality = 75): EncodedImage;
    public function toGif(): EncodedImage;
    public function toPng(): EncodedImage;
    public function pickColors(int $x, int $y): CollectionInterface;
    public function text(string $text, int $x, int $y, ?callable $init = null): ImageInterface;
    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface;
    public function greyscale(): ImageInterface;
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
    public function drawEllipse(int $x, int $y, ?callable $init = null): ImageInterface;
    public function drawLine(callable $init = null): ImageInterface;
    public function drawPolygon(callable $init = null): ImageInterface;
    public function sharpen(int $amount = 10): ImageInterface;
    public function flip(): ImageInterface;
    public function flop(): ImageInterface;
    public function getWidth(): int;
    public function getHeight(): int;
    public function destroy(): void;
}
