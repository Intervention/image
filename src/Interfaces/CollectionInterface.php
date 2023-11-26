<?php

namespace Intervention\Image\Interfaces;

use Traversable;

interface CollectionInterface extends Traversable
{
    /**
     * Determine if the collection has item at given key
     *
     * @param int|string $key
     * @return bool
     */
    public function has(int|string $key): bool;

    /**
     * Add item to collection
     *
     * @param mixed $item
     * @return CollectionInterface
     */
    public function push($item): CollectionInterface;

    /**
     * Return item for given key or return default is key does not exist
     *
     * @param int|string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(int|string $key, $default = null): mixed;

    /**
     * Return item at given numeric position starting at 0
     *
     * @param int $key
     * @param mixed $default
     * @return mixed
     */
    public function getAtPosition(int $key = 0, $default = null): mixed;

    /**
     * Return first item in collection
     *
     * @return mixed
     */
    public function first(): mixed;

    /**
     * Return last item in collection
     *
     * @return mixed
     */
    public function last(): mixed;

    /**
     * Return item count of collection
     *
     * @return int
     */
    public function count(): int;

    /**
     * Empty collection
     *
     * @return CollectionInterface
     */
    public function empty(): CollectionInterface;
}
