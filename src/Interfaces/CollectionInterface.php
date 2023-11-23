<?php

namespace Intervention\Image\Interfaces;

use Traversable;

interface CollectionInterface extends Traversable
{
    public function has(int|string $key): bool;
    public function push($item): CollectionInterface;
    public function get(int|string $key, $default = null);
    public function getAtPosition(int $key = 0, $default = null);
    public function first();
    public function last();
    public function count(): int;
    // public function empty(): CollectionInterface;
}
