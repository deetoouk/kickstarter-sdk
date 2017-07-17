<?php

namespace JTDSoft\EssentialsSdk\Core;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use JTDSoft\EssentialsSdk\Contracts\Arrayable;

/**
 * Class Collection
 *
 * @package JTDSoft\EssentialsSdk
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, Arrayable, JsonSerializable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param array $items
     */
    public function __construct($items = null)
    {
        if (!isset($items)) {
            return;
        }

        $this->setItems($items);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        if (is_array($items)) {
            $this->items = $items;
        } elseif ($items instanceof Collection) {
            $this->items = $items->getItems();
        } elseif ($items instanceof Arrayable) {
            $this->items = $items->toArray();
        } elseif (is_scalar($items)) {
            $this->items = [$items];
        }
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param $keyBy
     *
     * @return static
     */
    public function keyBy($keyBy)
    {
        $results = [];

        foreach ($this->items as $item) {
            $results[$item->$keyBy] = $item;
        }

        return new static($results);
    }

    /**
     * @param $keyBy
     *
     * @return static
     */
    public function groupBy($keyBy)
    {
        $results = [];

        foreach ($this->items as $item) {
            $results[$item->$keyBy][] = $item;
        }

        return new static($results);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Get the first item from the collection.
     *
     * @param  callable|string|null $callback
     * @param null                  $value
     * @param bool                  $strict
     *
     * @return mixed
     */
    public function first($callback = null, $value = null, $strict = true)
    {
        if (!$callback) {
            return count($this->items) > 0 ? reset($this->items) : null;
        }

        if (!is_callable($callback)) {
            return $this->where($callback, $value, $strict)->first();
        }

        return $this->filter($callback)->first();
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $strict
     *
     * @return Collection
     */
    public function where($key, $value, $strict = true)
    {
        return $this->filter(function ($item) use ($key, $value, $strict) {
            if (is_object($item)) {
                return $strict ? $item->$key === $value : $item->$key == $value;
            } else {
                return $strict ? $item[$key] === $value : $item[$key] == $value;
            }
        });
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string $key
     * @param  array  $values
     * @param  bool   $strict
     *
     * @return static
     */
    public function whereIn($key, $values, $strict = false)
    {
        return $this->filter(function ($item) use ($key, $values, $strict) {
            if (is_object($item)) {
                return in_array($item->$key, $values, $strict);
            } else {
                return in_array($item[$key], $values, $strict);
            }
        });
    }

    /**
     * @param callable $filter
     *
     * @return static
     */
    public function filter(callable $filter = null)
    {
        if ($filter) {
            return new static(array_filter($this->items, $filter));
        }

        return new static(array_filter($this->items));
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable $callback
     *
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable $callback
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Sort through each item with a callback.
     *
     * @param  callable|null $callback
     *
     * @return static
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;

        $callback ? uasort($items, $callback) : natcasesort($items);

        return new static($items);
    }
}
