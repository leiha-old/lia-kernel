<?php

namespace Lia\KernelBundle\Annotation;

use Lia\Library\Exception\LogicException;

class ReflexionProperty
{
    private $reflectionObject;

    private $reflectionProperty;

    public function __construct(ReflectionObject $reflectionObject, \ReflectionProperty $reflectionProperty){
        $this->reflectionObject   = $reflectionObject;
        $this->reflectionProperty = $reflectionProperty;
    }

    public function __call($method, $args){
        if(method_exists($this->reflectionProperty, $method)){
            return call_user_func_array(array($this->reflectionProperty, $method), $args);
        }

        throw new LogicException(
            'Method [%1s] is undefined in class [%2s]', array(
                $method, get_called_class()
            )
        );
    }

    public function getClassName(){
        return $this->reflectionProperty->getDeclaringClass()->getName();
    }

    public function getAnnotations($annotationsClass=null){
        return $this->reflectionObject
            ->getPropertyAnnotations($this->reflectionProperty, $annotationsClass)
            ;
    }

    public function getAnnotation($annotationClass=null, $multiple=false){
        return $this->reflectionObject
            ->getPropertyAnnotation($this->reflectionProperty, $annotationClass, $multiple)
            ;
    }

    public function hasAnnotation($annotationsClass){
        return $this->getAnnotations($annotationsClass);
    }

}