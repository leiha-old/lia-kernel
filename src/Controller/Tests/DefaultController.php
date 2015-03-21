<?php

namespace Lia\KernelBundle\Controller\Tests;

use Lia\KernelBundle\Controller\ControllerBase;

class DefaultController
    extends ControllerBase
{

    public function defaultAction(){

        return $this->render('LiaKernelCoreBundle::layout.html.twig');
    }
}