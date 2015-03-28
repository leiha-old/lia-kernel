<?php

namespace Lia\KernelBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;

class Driver
{
    /**
     * @var AnnotationReader|Reader
     */
    private $reader;

    public function __construct(Reader $reader=null)
    {
        $this->reader = $reader ? $reader : new AnnotationReader();
    }

    /**
     * @param string        $originalObject
     * @param array|string  $annotationsClass
     * @return \stdClass
     */
    public function convert($originalObject, $annotationsClass)
    {
        $convertedObject      = new \stdClass;
        $reflectionObject     = new \ReflectionObject(new $originalObject);
        $reflectionProperties = $reflectionObject->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $tmp = array();
            foreach ((array)$annotationsClass as $annotationClass) {
                $annotation = $this->reader->getPropertyAnnotation($reflectionProperty, $annotationClass);
                if (null !== $annotation) {
                    $tmp[$annotationClass] = $annotation;
                }
            }

            if($tmp)
                $convertedObject->{$reflectionProperty->getName()} = $tmp;
        }

        return $convertedObject;
    }

} 