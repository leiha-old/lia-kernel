<?php

namespace Lia\KernelBundle\Theme;

use Lia\KernelBundle\Bag\CollectionBag;

class BuilderBag
{
    /**
     * @var CollectionBag
     */
    public $files;

    /**
     * @var CollectionBag
     */
    public $blocks;

    public function __construct()
    {
        $this->files  = new CollectionBag();
        $this->blocks = new CollectionBag();
    }
} 