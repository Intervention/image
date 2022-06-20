<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\CollectionInterface;
use ArrayIterator;
use Countable;
use Traversable;
use IteratorAggregate;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

class Collection implements CollectionInterface, IteratorAggregate, Countable
{
    public function __construct(protected array $items = [])
    {
        //
    }

    /**
     * Static constructor
     *
     * @param  array  $items
     * @return self
     */
    public static function create(array $items = []): self
    {
        return new self($items);
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
     * @return integer
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Append new item to collection
     *
     * @param  mixed $item
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
    public function first()
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
    public function last()
    {
        if ($item = end($this->items)) {
            return $item;
        }

        return null;
    }

    /**
     * Return item with given key
     *
     * @param  integer $key
     * @return mixed
     */
    public function get(int $key = 0, $default = null)
    {
        if (! array_key_exists($key, $this->items)) {
            return $default;
        }

        return $this->items[$key];
    }

    public function has(int $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function query(string $query, $default = null)
    {
        $items = $this->getItemsFlat();
        if (!array_key_exists($query, $items)) {
            return $default;
        }

        return $items[$query];
    }

    public function map(callable $callback): self
    {
        $items = array_map(function ($item) use ($callback) {
            return $callback($item);
        }, $this->items);

        return new self($items);
    }

    public function pushEach(array $data, ?callable $callback = null): CollectionInterface
    {
        if (! is_iterable($data)) {
            throw new RuntimeException('Unable to iterate given data.');
        }

        foreach ($data as $item) {
            $this->push(is_callable($callback) ? $callback($item) : $item);
        }

        return $this;
    }

    private function getItemsFlat(): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($this->items)
        );

        $items = [];
        foreach ($iterator as $value) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $items[join('.', $keys)] = $value;
        }

        return $items;
    }
}
