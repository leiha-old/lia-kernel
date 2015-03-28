<?php

namespace Lia\KernelBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;

class ReflectionObject
{
    /**
     * @var AnnotationReader|Reader
     */
    private $reader;

    /**
     * @var \ReflectionObject
     */
    private $object;

    public function __construct($className, Reader $reader=null){
        $this->object = new \ReflectionObject(new $className);
        $this->reader = $reader ? $reader : new AnnotationReader();
    }

    // ------------------------------------------------------------------------------

    public function getClassAnnotation($annotationClass, $multiple=false){
        $annotation = $this->getClassAnnotations($annotationClass);
        if($annotation){
            $annotation = current($annotation);
            return $multiple ? $annotation : $annotation[0];
        }
    }

    /**
     * @param array|string $annotationsClass
     * @return array
     */
    public function getClassAnnotations($annotationsClass=null){
        return $this->iterateForAnnotations(
            $annotationsClass,
            $this->reader->getClassAnnotations($this->object)
        );
    }


    /**
     * @param \ReflectionMethod $reflectionElement
     * @param $annotationClass
     * @param bool $multiple
     * @return mixed
     */
    public function getMethodAnnotation(\ReflectionMethod $reflectionElement, $annotationClass, $multiple=false){
        $annotation = $this->getMethodAnnotations($reflectionElement, $annotationClass);
        if($annotation){
            $annotation = current($annotation);
            return $multiple ? $annotation : $annotation[0];
        }
    }

    /**
     * @param string       $methodName
     * @param array|string $annotationsClass
     * @return array
     */
    public function getMethodAnnotations($methodName, $annotationsClass=null){
        $reflectionMethod = $methodName instanceof \ReflectionMethod
            ? $methodName
            : $this->getMethod($methodName)
        ;

        return $this->iterateForAnnotations(
            $annotationsClass,
            $this->reader->getMethodAnnotations($reflectionMethod)
        );
    }

    /**
     * @param array|string|null $annotationsClass
     * @return array
     */
    public function getMethodsAnnotations($annotationsClass=null){
        return $this->getAnnotations('Methods', $annotationsClass);
    }

    /**
     * @param $methodName
     * @return \ReflectionMethod
     */
    public function getMethod($methodName){
        return $this->object->getMethod($methodName);
    }

    /**
     * @return \ReflectionMethod[]
     */
    public function getMethods(){
        return $this->object->getMethods();
    }

    /**
     * @param \ReflectionProperty $reflectionElement
     * @param null $annotationClass
     * @param bool $multiple
     * @return mixed
     */
    public function getPropertyAnnotation(\ReflectionProperty $reflectionElement, $annotationClass=null, $multiple=false){
        $annotation = $this->getPropertyAnnotations($reflectionElement, $annotationClass);
        if($annotation){
            $annotation = current($annotation);
            return $multiple ? $annotation : $annotation[0];
        }
    }

    /**
     * @param string|\ReflectionProperty $propertyName
     * @param array|string $annotationsClass
     * @return array
     */
    public function getPropertyAnnotations($propertyName, $annotationsClass=null){
        $reflectionProperty = $propertyName instanceof \ReflectionProperty
            ? $propertyName
            : $this->getProperty($propertyName)
        ;

        return $this->iterateForAnnotations(
            $annotationsClass,
            $this->reader->getPropertyAnnotations($reflectionProperty)
        );
    }


    /**
     * @param array|string|null $annotationsClass
     * @return array
     */
    public function getPropertiesAnnotations($annotationsClass=null){
        return $this->getAnnotations('Properties', $annotationsClass);
    }

    /**
     * @param string $propertyName
     * @return \ReflectionMethod
     */
    public function getProperty($propertyName){
        return new ReflexionProperty($this, $this->object->getProperty($propertyName));
    }

    /**
     * @return ReflexionProperty[]
     */

    public function getProperties(){
        $properties = $this->object->getProperties();

        $tmp = array();
        foreach($properties as $property){
            $tmp[$property->getName()] = new ReflexionProperty($this, $property);
        }
        return $tmp;
    }

    /**
     * @param array|string $annotationsClass
     * @param $annotations
     * @return array
     */
    private function iterateForAnnotations($annotationsClass, $annotations){
        if(!$annotationsClass){
            return $annotations;
        }

        $extracted = array();
        foreach ($annotations as $annotation) {
            foreach ((array)$annotationsClass as $annotationClass) {
                if ($annotation instanceof $annotationClass) {
                    $extracted[$annotationClass][] = (array)$annotation;
                }
            }
        }

        return $extracted;
    }

    /**
     * @param string            $type
     * @param array|string|null $annotationsClass
     * @return array
     */
    private function getAnnotations($type, $annotationsClass=null){
        $reflectionElements = $this->{'get'.$type}();

        $extracted = array();
        foreach($reflectionElements as $reflectionElement){
            $extracted[$reflectionElement->getName()] =
                $this->{'get'.$type.'Annotations'}($reflectionElement, $annotationsClass)
            ;
        }

        return $extracted;
    }
}