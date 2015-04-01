<?php

namespace Lia\KernelBundle\Bag;

use Lia\KernelBundle\Cache\CacheInterface;

trait SessionBagTrait
{
    use CommonBagTrait;

    /**
     * @var bool
     */
    protected $sessionStacking = true;

    /**
     * @return CacheInterface
     */
    protected function getCacheEngine()
    {
        if(!$this->cacheEngine) {
            $this->cacheEngine = $this->getService('lia.factory.cache.session');
        }
        return $this->cacheEngine;
    }

    /**
     * @param bool $enable
     * @param bool $restore
     * @return $this
     */
    public function enableSessionStacking($enable=true, $restore=false){
        $this->sessionStacking = $enable;
        if($enable && $restore) {
            $this->restoreDataSinceSession();
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSessionStacking(){
        return $this->sessionStacking;
    }

    /**
     * @return bool
     */
    public function restoreDataSinceSession(){
        return $this->restoreData();
    }

    /**
     * @return $this
     */
    public function saveDataInSession(){
        return $this->saveData();
    }
} 