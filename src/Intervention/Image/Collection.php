<?php

namespace Intervention\Image;

class Collection
{
    /**
     * The items of the collection.
     *
     * @var array
     */
    protected $items = array();

    /**
     * Create a collection.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct($items = array())
    {
        $this->items = $items;
    }

    /**
     * Returns encoded image data in string conversion
     *
     * @return string
     */
    public function __toString()
    {
        return $this->first();
    }

    /**
     * Count items in collection
     *
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Return first item in collection
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Returns last item in collection
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->items);
    }
}
