<?php

namespace Lia\KernelBundle\Bag;

use Lia\KernelBundle\Cache\CacheInterface;
use Lia\KernelBundle\Tools\ContainerAwareTrait;

trait CommonBagTrait
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CacheInterface
     */
    protected $cacheEngine;

    /**
     * @param string $type
     * @return array|string
     */
    abstract public function getAll($type='php');

    /**
     * @return CacheInterface
     */
    abstract protected function getCacheEngine();

    /**
     * @param array $values
     * @param bool $silent
     * @return $this
     */
    abstract public function set(array $values, $silent=true);

    /**
     * @return bool
     */
    protected function restoreData(){
        $name = $this->getBagName(false);
        $data = $this->getCacheEngine()->get($name);
        if(null !== $data) {
            $this->set($data);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function saveData(){
        $name = $this->getBagName(false);
        return $this->getCacheEngine()->set($name, $this->getAll());
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setBagName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * @param bool $silent
     * @return string
     */
    public function getBagName($silent=true){
        if(!$silent && !is_string($this->name))
            throw new \LogicException('Name of Bag is\'nt set');

        return $this->name;
    }

} 