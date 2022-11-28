<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 9:30
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Model;

/**
 * Class Collection
 * @package SB\Model
 */
abstract class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $collection = array();

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    public function offsetExists($offset) {
        return isset($this->collection[$offset]);
    }

    public function offsetGet($offset) {
        return $this->collection[$offset] ?? null;
    }

    public function offsetSet($offset, $value) {
        if (null === $offset) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->collection[$offset]);
    }

    public function count()
    {
        return \count($this->collection);
    }
}