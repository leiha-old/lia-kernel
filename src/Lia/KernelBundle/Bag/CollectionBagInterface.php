<?php

namespace Lia\KernelBundle\Bag;

interface CollectionBagInterface
    extends \IteratorAggregate
{
    /**
     * @param string $itemName
     * @param bool   $silent
     * @return bool
     */
    public function has($itemName, $silent=true);

    /**
     * @param string $itemName
     * @param mixed  $value
     * @param bool $returnAdded
     * @return mixed
     */
    public function add($itemName, $value, $returnAdded=false);

    /**
     * @param array $values
     * @param bool $reset
     * @return CollectionBagInterface
     */
    public function set(array $values, $reset=false);

    /**
     * @param string $itemName
     * @return CollectionBagInterface
     */
    public function remove($itemName);

    /**
     * @param string $itemName
     * @param mixed $default
     * @return mixed
     */
    public function get($itemName='', $default=null);

    /**
     * @param callable $callback
     * @param string $mode
     * @return mixed
     */
    public function iterate(Callable $callback, $mode='concat');
}