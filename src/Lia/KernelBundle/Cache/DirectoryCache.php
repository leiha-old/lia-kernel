<?php

namespace Lia\KernelBundle\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DirectoryCache
    extends CacheBase
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        parent::__construct($container);
        $this->cacheDir = $this->container->getParameter('kernel.cache_dir');
    }

    /**
     * @param string $identifier
     * @param mixed  $data
     * @return int
     */
    public function set($identifier, $data)
    {
        $dir = $this->cacheDir.dirname($identifier);
        if(!is_dir($dir)){
            mkdir($dir, 0755);
        }
        return file_put_contents($this->cacheDir.$identifier, serialize($data));
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get($identifier)
    {
        $path = $this->cacheDir.$identifier;
        if(is_file($path))
        {
            return unserialize(file_get_contents($path));
        }
        return false;
    }
}