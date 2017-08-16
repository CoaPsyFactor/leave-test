<?php

namespace Modules\Collection\GenericCollection;

use Modules\Singleton\Singleton;

class GenericCollection implements IGenericCollection
{

    use Singleton;

    /** @var array */
    protected $_items = [];

    /** @var int Current iterator index */
    private $_currentIndex = 0;

    protected function __construct(array $items = [])
    {

        foreach ($items as $item) {

            $this[] = $item;
        }
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {

        return $this->_items[$this->_currentIndex];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {

        $this->_currentIndex++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {

        return $this->_currentIndex;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {

        return isset($this->_items[$this->_currentIndex]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {

        $this->_currentIndex = 0;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {

        return isset($this->_items[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {

        return $this->_items[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {

        if (null === $offset) {

            $offset = $this->count();
        }

        $this->_items[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {

        unset($this->_items[$offset]);
    }

    /**
     *
     * Retrieve amount of items in collection
     *
     * @return int
     */
    public function count()
    {

        return count($this->_items);
    }

    /**
     *
     * Checks whether item bag is empty or not
     *
     * @return bool
     */
    public function isEmpty()
    {

        return 0 === $this->count();
    }

    /**
     * @param \Closure $filterCallback Returns Boolean if its TRUE item will be append to result collection, otherwise it will be ignored
     * @return IGenericCollection|$this
     */
    public function filter(\Closure $filterCallback)
    {

        $results = [];

        foreach ($this as $item) {

            if (false === $filterCallback($item)) {

                continue;
            }

            $results[] = $item;
        }

        return forward_static_call_array([static::class, 'getNewInstance'], [$results]);
    }

    /**
     * Clears out collection
     *
     * @return void
     */
    public function clear()
    {

        $this->_items = [];

        $this->rewind();
    }

    /**
     *
     * Returns collection in type of array
     *
     * @return array
     */
    public function toArray()
    {

        return array_merge([], $this->_items);
    }
}