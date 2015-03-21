<?php

namespace Lia\KernelBundle\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DirectoryCache
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->cacheDir  = $this->container->getParameter('kernel.cache_dir');
    }

    public function setSubPath($cacheDir){
        $this->cacheDir .= $cacheDir;
        return $this;
    }

    /**
     * @param string $file
     * @param mixed  $data
     * @return int
     */
    public function set($file, $data)
    {
        $dir = $this->cacheDir.dirname($file);
        if(!is_dir($dir)){
            mkdir($dir, 0755);
        }
        return file_put_contents($this->cacheDir.$file, serialize($data));
    }

    /**
     * @param string $file
     * @return mixed
     */
    public function get($file)
    {
        if(is_file($file)){
            return unserialize(file_get_contents($file));
        }
        return false;
    }
}