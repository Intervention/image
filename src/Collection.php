<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\CollectionInterface;
use ArrayIterator;
use Countable;
use Traversable;
use IteratorAggregate;

class Collection implements CollectionInterface, IteratorAggregate, Countable
{
    public function __construct(protected array $items = [])
    {
    }

    /**
     * Static constructor
     *
     * @param array $items
     * @return self
     */
    public static function create(array $items = []): self
    {
        return new self($items);
    }

    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Returns Iterator
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Count items in collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Append new item to collection
     *
     * @param mixed $item
     * @return CollectionInterface
     */
    public function push($item): CollectionInterface
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Return first item in collection
     *
     * @return mixed
     */
    public function first(): mixed
    {
        if ($item = reset($this->items)) {
            return $item;
        }

        return null;
    }

    /**
     * Returns last item in collection
     *
     * @return mixed
     */
    public function last(): mixed
    {
        if ($item = end($this->items)) {
            return $item;
        }

        return null;
    }

    /**
     * Return item at given position starting at 0
     *
     * @param int $key
     * @return mixed
     */
    public function getAtPosition(int $key = 0, $default = null): mixed
    {
        if ($this->count() == 0) {
            return $default;
        }

        $positions = array_values($this->items);
        if (!array_key_exists($key, $positions)) {
            return $default;
        }

        return $positions[$key];
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::get()
     */
    public function get(int|string $query, $default = null): mixed
    {
        if ($this->count() == 0) {
            return $default;
        }

        if (is_int($query) && array_key_exists($query, $this->items)) {
            return $this->items[$query];
        }

        if (is_string($query) && strpos($query, '.') === false) {
            return array_key_exists($query, $this->items) ? $this->items[$query] : $default;
        }

        $query = explode('.', (string) $query);

        $result = $default;
        $items = $this->items;
        foreach ($query as $key) {
            if (!is_array($items) || !array_key_exists($key, $items)) {
                $result = $default;
                break;
            }

            $result = $items[$key];
            $items = $result;
        }

        return $result;
    }

    public function map(callable $callback): self
    {
        $items = array_map(function ($item) use ($callback) {
            return $callback($item);
        }, $this->items);

        return new self($items);
    }

    /**
     * Run callback on each item of the collection an remove it if it does not return true
     *
     * @param callable $callback
     * @return Collection
     */
    public function filter(callable $callback): self
    {
        $items = array_filter($this->items, function ($item) use ($callback) {
            return $callback($item);
        });

        return new self($items);
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::empty()
     */
    public function empty(): CollectionInterface
    {
        $this->items = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see CollectionInterface::slice()
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        $this->items = array_slice($this->items, $offset, $length);

        return $this;
    }
}
