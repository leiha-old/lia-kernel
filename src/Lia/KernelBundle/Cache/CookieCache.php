<?php

namespace Lia\KernelBundle\Cache;

class CookieCache
    extends CacheBase
{

    /**
     * @param string $identifier
     * @param mixed $data
     * @return $this
     */
    public function set($identifier, $data)
    {
        setcookie($identifier, json_encode($data));
        return $this;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get($identifier)
    {
        if(isset($_COOKIE[$identifier])){
            return json_decode($_COOKIE[$identifier], true);
        }
        return false;
    }
}