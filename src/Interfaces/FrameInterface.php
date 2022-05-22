<?php

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    public function toImage(): ImageInterface;
    public function getCore();
    public function setCore($core): FrameInterface;
    public function getSize(): SizeInterface;
    public function getDelay(): float;
    public function setDelay(float $delay): FrameInterface;
    public function getDispose(): int;
    public function setDispose(int $dispose): FrameInterface;
    public function setOffset(int $left, int $top): FrameInterface;
    public function getOffsetLeft(): int;
    public function setOffsetLeft(int $offset): FrameInterface;
    public function getOffsetTop(): int;
    public function setOffsetTop(int $offset): FrameInterface;
}
