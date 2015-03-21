<?php

namespace Lia\KernelBundle\Theme;

class JavascriptBuilder
    extends BuilderBase
{

    /**
     * @return string
     */
    public function renderTop()
    {
        return $this->render($this->top);
    }

    /**
     * @return string
     */
    public function renderBottom()
    {
        return $this->render($this->bottom);
    }

    /**
     * @param BuilderBag $items
     * @return string
     */
    protected function render(BuilderBag $items){
        $render = '';
        foreach($items->files->get() as $file){
            $render .= "\n".'<script type="text/javascript" src="'.$file.'"></script>';
        }

        foreach($items->blocks->get() as $block){
            $render .= "\n".'<script type="text/javascript">'.$block.'</script>';
        }
        return $render;
    }
}