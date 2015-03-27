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
     * @var string
     */
    private $bundleName;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    protected function getBundleName()
    {
        if(!$this->bundleName) {
            $c = get_called_class();
            $this->bundleName = substr($c, 0, strpos($c, 'Bundle\\'));
            $this->bundleName = str_replace('\\', '', $this->bundleName);
        }
        return $this->bundleName;
    }

    /**
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->config = new CollectionBag($config);
    }
}