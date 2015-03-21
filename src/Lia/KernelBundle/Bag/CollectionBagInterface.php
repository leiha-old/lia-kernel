<?php

namespace Lia\KernelBundle\Bag;

interface CollectionBagInterface
{
    public function has($itemName);

    public function add($itemName, $value, $returnAdded=false);

    public function set(array $values, $reset=false);

    public function remove($itemName);

    public function get($itemName='', $default=null);

    public function iterate(Callable $callback, $mode='concat');
}