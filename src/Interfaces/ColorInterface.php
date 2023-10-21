<?php

namespace Intervention\Image\Interfaces;

interface ColorInterface
{
    public function __toString(): string;
    public function toString(): string;
    public function toArray(): array;
    public function toHex(): string;
    public function channels(): array;
    public function channel(string $classname): ColorChannelInterface;
    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface;
    public function isGreyscale(): bool;
}
