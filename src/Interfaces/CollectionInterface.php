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
     * Determine if the collection has item at given key.
     */
    public function has(int|string $key): bool;

    /**
     * Add item to collection.
     *
     * @return CollectionInterface<int|string, mixed>
     */
    public function push(mixed $item): self;

    /**
     * Return item for given key or return default is key does not exist.
     */
    public function get(int|string $key, mixed $default = null): mixed;

    /**
     * Set item in collection.
     */
    public function set(int|string $key, mixed $item): self;

    /**
     * Return item at given numeric position starting at 0.
     */
    public function at(int $key = 0, mixed $default = null): mixed;

    /**
     * Return first item in collection.
     */
    public function first(): mixed;

    /**
     * Return last item in collection.
     */
    public function last(): mixed;

    /**
     * Return item count of collection.
     */
    public function count(): int;

    /**
     * Map each item of collection by given callback.
     */
    public function map(callable $callback): self;

    /**
     * Run callback on each item of the collection an remove it if it does not return true.
     */
    public function filter(callable $callback): self;

    /**
     * Empty collection.
     *
     * @return CollectionInterface<int|string, mixed>
     */
    public function clear(): self;

    /**
     * Transform collection as array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(): array;

    /**
     * Extract items based on given values and discard the rest.
     *
     * @return CollectionInterface<int|string, mixed>
     */
    public function slice(int $offset, ?int $length = 0): self;
}
