<?php

namespace Lia\KernelBundle\Bag;

class CollectionBag
    implements CollectionBagInterface
{
    /**
     * @var array
     */
    private $items = array();

    /**
     * @param array $items
     */
    public function __construct(array $items=array())
    {
        $this->set($items);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->__sleep());
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return $this->items;
    }

    /**
     * @param string $itemName
     * @param bool $silent
     * @return bool
     */
    public function has($itemName, $silent=true)
    {
        $exist = array_key_exists($itemName, $this->items);
        if(!$silent && !$exist) {
            //throw new LogicException('Element [%1$s] not registered !', array($itemName));
        }
        return $exist;
    }

    /**
     * @param string $itemName
     * @param mixed $value
     * @param bool $returnAdded
     * @return CollectionBag
     */
    public function add($itemName, $value, $returnAdded=false)
    {
        $this->items[$itemName] = $value;
        return $returnAdded
            ? $this->items[$itemName]
            : $this
            ;
    }

    /**
     * @param array $values
     * @param bool $reset
     * @return CollectionBag
     */
    public function set(array $values, $reset=false)
    {
        $this->items = $reset
            ? $values
            : array_merge($this->items, $values)
        ;
        return $this;
    }

    /**
     * @param string $itemName
     * @return CollectionBag
     */
    public function remove($itemName)
    {
        if($this->has($itemName)){
            unset($this->items[$itemName]);
        }
        return $this;
    }

    /**
     * @param string $itemName
     * @param mixed|null $default
     * @return mixed
     */
    public function get($itemName='', $default=null)
    {
        if(!$itemName)
            return $this->items;

        return $this->has($itemName)
            ? $this->items[$itemName]
            : $default;
    }

    /**
     * @param array $items
     * @param callable $callback
     * @param string $mode Available : [ string | array | array[] ]
     * @return array|string
     */
    private function iterateOn(array $items, Callable $callback, $mode='string')
    {
        switch($mode){
            case 'array[]'  :
                $concat = array();
                foreach($items as $itemName=>$item) {
                    $concat[] = $callback($item, $itemName);
                }
                break;
            case 'array'  :
                $concat = array();
                foreach($items as $itemName=>$item) {
                    $concat[$itemName] = $callback($item, $itemName);
                }
                break;
            case 'string' :
            default:
                $concat = '';
                foreach($items as $itemName=>$item) {
                    $concat .= $callback($item, $itemName);
                }
                break;
        }
        return $concat;
    }

    /**
     * @param string $itemName
     * @param callable $callback
     * @param string $mode Available : [ string | array | array[] ]
     * @return array|string
     */
    public function iterateOnItem($itemName, Callable $callback, $mode='string')
    {
        if(!$this->has($itemName)){
            // TODO : Make an Exception
        }
        return $this->iterateOn($this->items[$itemName], $callback, $mode);
    }

    /**
     * @param callable $callback
     * @param string $mode Available : [ string | array | array[] ]
     * @return array|string
     */
    public function iterate(Callable $callback, $mode='string')
    {
        return $this->iterateOn($this->items, $callback, $mode);
    }

    /**
     * @param string $mode
     * @return array|null|string
     */
    public function getAll($mode='php'){
        switch($mode){
            case 'json' :
                return json_encode($this->items);
                break;
            case 'php':
            default :
                return $this->get();
                break;
        }
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Returns all keys of elements
     *
     * @return array
     */
    public function keys() {
        return array_keys($this->items);
    }

    /**
     * Returns the number of elements.
     *
     * @return int The number of elements
     */
    public function count() {
        return count($this->items);
    }
}