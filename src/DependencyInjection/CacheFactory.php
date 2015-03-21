<?php

namespace Lia\KernelBundle\DependencyInjection;

use Lia\KernelBundle\Service\ServiceBase;

class CacheFactory
    extends ServiceBase
{
    public function getCacheDirectory($subPath = '')
    {
        return $this->container->get('lia.factory.cache.directory')
            ->setSubPath($subPath)
            ;
    }
}