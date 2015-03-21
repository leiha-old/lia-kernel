<?php

namespace Lia\KernelBundle\Theme;

class StyleSheetsBuilder
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
            $render .= "\n".'<link rel="stylesheet" href="'.$file.'" />';
        }

        foreach($items->blocks->get() as $block){
            $render .= "\n".'<style type="text/css">'.$block.'</style>';
        }
        return $render;
    }
}