<?php

namespace Lia\KernelBundle\Cache;

class SessionCache
    extends CacheBase
{

    /**
     * @param string $identifier
     * @param mixed $data
     * @return int
     */
    public function set($identifier, $data)
    {
        $_SESSION[$identifier] = $data;
        return $this;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get($identifier)
    {
        if(isset($_SESSION[$identifier])) {
            return $_SESSION[$identifier];
        }
        return null;
    }
}