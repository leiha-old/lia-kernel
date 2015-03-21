<?php

namespace Lia\KernelBundle\Theme;

abstract class BuilderBase
{
    /**
     * @var BuilderBag
     */
    public $top;

    /**
     * @var BuilderBag
     */
    public $bottom;

    public function __construct()
    {
        $this->top    = new BuilderBag();
        $this->bottom = new BuilderBag();
    }

    abstract function renderTop();
    abstract function renderBottom();
} 