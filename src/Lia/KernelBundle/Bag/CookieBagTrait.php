<?php

namespace Lia\KernelBundle\Bag;

use Lia\KernelBundle\Cache\CacheInterface;

trait CookieBagTrait
{
    use CommonBagTrait;

    /**
     * @var bool
     */
    protected $cookieStacking = true;

    /**
     * @return CacheInterface
     */
    protected function getCacheEngine()
    {
        if(!$this->cacheEngine) {
            $this->cacheEngine = $this->getService('lia.factory.cache.cookie');
        }
        return $this->cacheEngine;
    }

    /**
     * @param bool $enable
     * @param bool $restore
     * @return $this
     */
    public function enableCookieStacking($enable=true, $restore=false){
        $this->cookieStacking = $enable;
        if($enable && $restore) {
            $this->restoreDataSinceCookie();
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function hasCookieStacking(){
        return $this->cookieStacking;
    }

    /**
     * @return bool
     */
    public function restoreDataSinceCookie(){
        return $this->restoreData();
    }

    /**
     * @return $this
     */
    public function saveDataInCookie(){
        return $this->saveData();
    }
} 