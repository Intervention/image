<?php

namespace Intervention\Image\Interfaces;

interface FrameInterface
{
    public function toImage(): ImageInterface;
    public function getCore();
    public function getDelay(): int;
    public function setDelay(int $delay): FrameInterface;
    public function getDispose(): int;
    public function setDispose(int $dispose): FrameInterface;
    public function setOffset(int $left, int $top): FrameInterface;
    public function getOffsetLeft(): int;
    public function setOffsetLeft(int $offset): FrameInterface;
    public function getOffsetTop(): int;
    public function setOffsetTop(int $offset): FrameInterface;
}
