<?php

namespace Intervention\Image\Interfaces;

interface ColorInterface
{
    public function red(): int;
    public function green(): int;
    public function blue(): int;
    public function alpha(): float;
    public function toArray(): array;
    public function toHex(string $prefix = ''): string;
    public function toInt(): int;
}
