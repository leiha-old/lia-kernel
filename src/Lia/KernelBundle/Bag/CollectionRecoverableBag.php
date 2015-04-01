<?php

namespace Lia\KernelBundle\Bag;

class CollectionRecoverableBag
    extends     CollectionBag
    implements  CollectionRecoverableBagInterface
{
    use CookieBagTrait, SessionBagTrait;

    /**
     * @return bool
     */
    public function restoreData(){
        if($this->hasCookieStacking()
            && $this->restoreDataSinceCookie()
        ) {
                return true;
        }
        else if($this->hasSessionStacking()
            && $this->restoreDataSinceSession()
        ) {
                return true;
        }
        return false;
    }
}