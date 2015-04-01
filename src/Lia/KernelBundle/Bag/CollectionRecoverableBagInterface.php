<?php

namespace Lia\KernelBundle\Bag;

interface CollectionRecoverableBagInterface
    extends CollectionBagInterface
{
    /**
     * @return bool
     */
    public function restoreData();
}