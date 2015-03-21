<?php

namespace Lia\KernelBundle\Template;

use Lia\KernelBundle\Bag\CollectionBag;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Template
    implements TemplateInterface,
               ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var CollectionBag
     */
    protected $vars;

    abstract protected function setTemplateVars(CollectionBag $vars);

    public function __construct()
    {
        $this->vars = new CollectionBag();
    }

    /**
     * @param string $pathname
     * @return string
     */
    protected function renderFile($pathname){
        $this->setTemplateVars($this->vars);
        return $this->container->get('twig')->render($pathname, $this->vars->getAll());
    }

    /**
     * @param string $string
     * @return string
     */
    protected function renderString($string){
        $this->setTemplateVars($this->vars);
        $twig = new \Twig_Environment(new \Twig_Loader_String(), ['autoescape'=> false]);
        return $twig->render($string, $this->vars->getAll());
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container=null){
        $this->container = $container;
    }
} 