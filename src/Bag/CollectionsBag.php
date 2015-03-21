<?php

namespace Lia\KernelBundle\Bag;

class CollectionsBag
{
    /**
     * @var CollectionBag[]
     */
    private $data = array();

    /**
     * This method can be overridden for change the type of element collection
     * <br /> She must return an instance of CollectionBagInterface
     * @param array $items
     * @return CollectionBagInterface
     */
    protected function createCollectionElement(array $items=array()){
        return new CollectionBag($items);
    }

    /**
     * @param string $collectionName
     * @param string $itemName
     * @return bool
     */
    public function has($collectionName, $itemName)
    {
        if(isset($this->data[$collectionName])){
            return $this->data[$collectionName]->has($itemName);
        }
        return false;
    }

    /**
     * @param string $collectionName
     * @param string $itemName
     * @return array|null
     */
    public function get($collectionName, $itemName)
    {
        if($this->has($collectionName, $itemName)){
            return $this->data[$collectionName]->get($itemName);
        }
        return null;
    }

    /**
     * @param string $collectionName
     * @param string $itemName
     * @return $this
     */
    public function remove($collectionName, $itemName)
    {
        if($this->has($collectionName, $itemName)){
            unset($this->data[$collectionName][$itemName]);
        }
        return $this;
    }

    /**
     * @param string $collectionName
     * @param string $itemName
     * @param mixed  $itemValue
     * @return $this
     */
    public function add($collectionName, $itemName, $itemValue)
    {
        if(!$collection = $this->getCollection($collectionName)) {
            /** @var CollectionBag $collection */
            $collection = $this->addCollection($collectionName);
        }
        $collection->add($itemName, $itemValue);
        return $this;
    }

    /**
     * @param string $collectionName
     * @param callable $callback
     * @param string $mode
     * @return array|null|string
     */
    public function iterate($collectionName, Callable $callback, $mode='concat')
    {
        $collection = $this->getCollection($collectionName);
        return $collection
            ? $collection->iterate($callback, $mode)
            : null
            ;
    }

    /**
     * @param array $values
     * @param bool $reset
     * @return $this
     */
    public function set(array $values, $reset=false)
    {
        $this->data = $reset
            ? $values
            : array_merge_recursive($this->data, $values)
        ;
        return $this;
    }

    /**
     * @param string $collectionName
     * @return CollectionBag|null
     */
    public function getCollection($collectionName)
    {
        return $this->hasCollection($collectionName)
            ? $this->data[$collectionName]
            : null
            ;
    }

    /**
     * @param string $collectionName
     * @param array $items
     * @return CollectionBag|CollectionsBag
     */
    public function addCollection($collectionName, array $items=array())
    {
        $collection = $this->createCollectionElement($items);
        if(!$collection instanceof CollectionBagInterface){
            // TODO: Make an exception
        }

        $this->data[$collectionName] = $collection;
        return count($items)
            ? $this
            : $this->data[$collectionName]
            ;
    }

    /**
     * @param string $collectionName
     * @return $this
     */
    public function removeCollection($collectionName)
    {
        if($this->hasCollection($collectionName)){
            unset($this->data[$collectionName]);
        }
        return $this;
    }

    /**
     * @param string $collectionName
     * @return bool
     */
    public function hasCollection($collectionName)
    {
        return isset($this->data[$collectionName]);
    }

    /**
     * @param callable $callback
     * @param string $mode Available : [ string | array | array[] | array[][] ]
     * @return array|string
     */
    public function iterateCollections(Callable $callback, $mode='string')
    {
        switch($mode){
            case 'array[][]'  :
                $concat = array();
                foreach($this->data as $collectionName => $collection){
                    foreach($collection->get() as $itemName => $item){
                        $concat[][] = $callback($item, $collectionName, $itemName);
                    }
                }
                break;
            case 'array[]'  :
                $concat = array();
                foreach($this->data as $collectionName => $collection){
                    foreach($collection->get() as $itemName => $item){
                        $concat[$collectionName][] = $callback($item, $collectionName, $itemName);
                    }
                }
                break;
            case 'array'  :
                $concat = array();
                foreach($this->data as $collectionName => $collection){
                    foreach($collection->get() as $itemName => $item){
                        $concat[$collectionName][$itemName] = $callback($item, $collectionName, $itemName);
                    }
                }
                break;
            case 'string' :
            default:
                $concat = '';
                foreach($this->data as $collectionName => $collection){
                    foreach($collection->get() as $itemName => $item){
                        $concat .= $callback($item, $collectionName, $itemName);
                    }
                }
                break;
        }
        return $concat;
    }
} 