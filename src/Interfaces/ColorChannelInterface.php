<?php

namespace Intervention\Image\Interfaces;

interface ColorChannelInterface
{
    public function value(): int;
    public function normalize(int $precision = 32): float;
    public function validate(mixed $value): mixed;
    public function min(): int;
    public function max(): int;
    public function toString(): string;
    public function __toString(): string;
}
