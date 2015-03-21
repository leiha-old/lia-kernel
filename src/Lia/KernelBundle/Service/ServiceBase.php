<?php

namespace Lia\KernelBundle\Service;

use Lia\KernelBundle\Bag\CollectionBag;
use Lia\KernelBundle\Tools\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ServiceBase
{
    use ContainerAwareTrait;

    /**
     * @var CollectionBag
     */
    protected $config;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param array $config
     */
    public function setConfiguration(array $config){
        $this->config = new CollectionBag($config);
    }
}