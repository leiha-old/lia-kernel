<?php

namespace Lia\KernelBundle\DependencyInjection;

use Lia\ThemeBundle\Core\AssetBag;
use Lia\ThemeBundle\Core\SubscriberBase;

class ThemeSubscriberAutoService
    extends SubscriberBase

{
    /**
     * @return string
     */
    public function getPathOfAsset()
    {
        return '/symfony/web/bundles/liakernelcore/';
    }

    /**
     * Allows to set the assets for the bundle
     * They will be on the top of the page
     * @param AssetBag $bag
     */
    public function setTop(AssetBag $bag)
    {

    }

    /**
     * Allows to set the assets for the bundle
     * They will be on the bottom of the page
     * @param AssetBag $bag
     */
    public function setBottom(AssetBag $bag)
    {
        $bag->javascript->files->set([
            'lia.prototype.js',
            'lia.core.js',
            'lia.Debug.js',
            'lia.Class.js',
            'lia.Interface.js',
            'lia.Exception.js',
            '__tests__/lia.Class.js'

        ]);
    }
}