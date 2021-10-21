<?php

namespace Intervention\Image\Interfaces;

interface CollectionInterface
{
    public function push($item): CollectionInterface;
    public function get(int $key);
    public function first();
    public function last();
    public function count(): int;
}
