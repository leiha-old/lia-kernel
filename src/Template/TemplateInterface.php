<?php

namespace Lia\KernelBundle\Template;

use Lia\KernelBundle\Bag\CollectionBag;
use Lia\KernelBundle\Tools\BuildableInterface;
use Lia\KernelBundle\Tools\RenderableInterface;

interface TemplateInterface
    extends BuildableInterface,
            RenderableInterface
{

}