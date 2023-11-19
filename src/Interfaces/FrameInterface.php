<?php

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    public function data();
    public function toImage(DriverInterface $driver): ImageInterface;
    public function setData($data): FrameInterface;
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
