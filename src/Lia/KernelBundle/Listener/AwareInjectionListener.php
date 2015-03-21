<?php

namespace Lia\KernelBundle\Listener;

use Lia\KernelBundle\Tools\ControllerAwareInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AwareInjectionListener
    extends ListenerBase
 {

    public function onKernelController(FilterControllerEvent $event)
    {
        $action     = $event->getController();
        $controller = $action[0];
        if (!is_array($controller)) {
            return;
        }

        if($controller instanceof ControllerAwareInterface){

        }

    }
} 