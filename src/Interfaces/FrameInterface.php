<?php

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    public function toImage(): ImageInterface;
    public function core();
    public function setCore($core): FrameInterface;
    public function size(): SizeInterface;
    public function delay(): float;
    public function setDelay(float $delay): FrameInterface;
    public function dispose(): int;
    public function setDispose(int $dispose): FrameInterface;
    public function setOffset(int $left, int $top): FrameInterface;
    public function offsetLeft(): int;
    public function setOffsetLeft(int $offset): FrameInterface;
    public function offsetTop(): int;
    public function setOffsetTop(int $offset): FrameInterface;
}
