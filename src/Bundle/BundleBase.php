<?php

namespace Lia\KernelBundle\Bundle;

use Lia\KernelBundle\Bag\CollectionBag;
use Lia\KernelBundle\Bag\CollectionsBag;
use Lia\KernelBundle\Tools\AliasableInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

abstract class BundleBase
    extends Bundle
    implements AliasableInterface
{
    /**
     * @var CollectionsBag
     */
    private $autoServices;

    public function __construct()
    {
        $this->autoServices = new CollectionsBag();

        $this->autoServices->add('subscriber', 'theme', new CollectionBag(array(
            'class' => 'DependencyInjection\ThemeSubscriberAutoService',
            'tags'  => new CollectionBag(array(
                'lia.service.theme'
            ))
        )));

        $this->autoServices->add('factory', '', new CollectionBag(array(
                'class' => 'DependencyInjection\FactoryAutoService',
                'tags'  => new CollectionBag(array())
        )));

        $this->autoServices->add('subscriber', 'twig', new CollectionBag(array(
            'class' => 'DependencyInjection\TwigExtension',
            'tags'  => new CollectionBag(array(
                'twig.extension'
            ))
        )));
    }

    /**
     * @return string
     */
    public function getLibraryAlias(){
        return 'lia';
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $this->registerServices($container);
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    protected function registerServices(ContainerBuilder $container)
    {
        $this->autoServices->iterateCollections(
            function(CollectionBag $service, $serviceType, $serviceSubType)
                use ($container)
            {
                // Build absolute namespace for the class
                $serviceId = $this->getLibraryAlias()
                    .'.'.$serviceType
                    .($serviceSubType ? '.'.$serviceSubType : '')
                    .'.'.$this->getAlias();

                if($container->has($serviceId))
                    return;

                // Build absolute path for the class file
                $servicePath = $this->getPath()
                    .DIRECTORY_SEPARATOR.str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $service->get('class')
                    )
                ;

                // Check if file of class exist if not exist stop current treatment
                if (!is_file($servicePath.'.php')) {
                    return;
                }

                // Create a service definition
                $definition = new Definition($this->getNamespace().'\\'.$service->get('class'),
                    array(new Reference('service_container'))
                );

                // Add service tags if present
                /** @var CollectionBag $tags */
                $tags = $service->get('tags');
                $tags->iterate(function($tag) use ($definition){
                    $definition->addTag($tag);
                });

                // Set the service in the container
                $container->setDefinition($serviceId, $definition);
            }
        );
    }
}
