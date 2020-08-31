<?php

namespace Meride;

use Meride\MerideObject;

class MerideCollection extends MerideObject implements \Countable, \IteratorAggregate
{
    public function __construct($data = [])
    {
        $dataCopy = [];
        if (is_array($data)) {
            $dataArrayObject = new \ArrayObject($data);
            $dataCopy = $dataArrayObject->getArrayCopy();
            foreach ($dataCopy as $key => $value) {
                $dataCopy[$key] = new MerideObject((array)$value);
            }
        }
        $this->data = $dataCopy;
    }
    /**
     * Count the number of the items
     *
     * @return integer
     */
    public function count()
    {
        return \count($this->data);
    }

    /**
     * @return \ArrayIterator an iterator for the data
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}