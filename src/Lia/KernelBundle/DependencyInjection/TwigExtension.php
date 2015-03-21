<?php

namespace Lia\KernelBundle\DependencyInjection;

use Lia\KernelBundle\Twig\ExtensionBase as BaseTwigExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigExtension
    extends BaseTwigExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'lia.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('lia_get', function ($serviceId) {
                return $this->get($serviceId);
            }),
            ['is_safe' => ['html']]
        ];
    }
}