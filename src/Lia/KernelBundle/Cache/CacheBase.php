<?php

namespace Lia\KernelBundle\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class CacheBase
    implements CacheInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $cacheDir = '';

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function setSubPath($cacheDir){
        $this->cacheDir .= $cacheDir;
        return $this;
    }
}