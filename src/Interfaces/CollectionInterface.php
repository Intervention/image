<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Traversable;

/**
 * @extends Traversable<int|string, mixed>
 */
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
     * @return CollectionInterface<int|string, mixed>
     */
    public function push($item): self;

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
     * @return CollectionInterface<int|string, mixed>
     */
    public function empty(): self;

    /**
     * Transform collection as array
     *
     * @return array<int|string, mixed>
     */
    public function toArray(): array;

    /**
     * Extract items based on given values and discard the rest.
     *
     * @param int $offset
     * @param null|int $length
     * @return CollectionInterface<int|string, mixed>
     */
    public function slice(int $offset, ?int $length = 0): self;
}
