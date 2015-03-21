<?php

namespace Lia\KernelBundle\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerAwareTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Method who can be overridden
     * for doing anything after initialization of container
     */
    protected function __onAfterSetContainer(){

    }

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->__onAfterSetContainer();
    }

    /**
     * @param string $serviceId
     * @return object
     */
    public function getService($serviceId)
    {
        $service = $this->container->get($serviceId);

        return $service;
    }
}