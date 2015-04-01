<?php

namespace Lia\KernelBundle\Cache;

interface CacheInterface
{
    /**
     * @param string $identifier
     * @param mixed  $data
     * @return int
     */
    public function set($identifier, $data);

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get($identifier);
}