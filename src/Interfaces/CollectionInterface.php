<?php

namespace Intervention\Image\Interfaces;

use Traversable;

interface CollectionInterface extends Traversable
{
    public function push($item): CollectionInterface;
    public function get(int $key, $default = null);
    public function has(int $key);
    public function first();
    public function last();
    public function count(): int;
}
