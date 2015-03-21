<?php

namespace Lia\KernelBundle\DependencyInjection;

use Lia\KernelBundle\Service\ServiceBase;
use Symfony\Component\Validator\Constraint;

class ConstraintsFactory
    extends ServiceBase
{
    protected $constraints = array(
        'AbstractComparison' => 'Symfony\Component\Validator\Constraints\AbstractComparison',
        'All' => 'Symfony\Component\Validator\Constraints\All',
        'Blank' => 'Symfony\Component\Validator\Constraints\Blank',
        'Callback' => 'Symfony\Component\Validator\Constraints\Callback',
        'CardScheme' => 'Symfony\Component\Validator\Constraints\CardScheme',
        'Choice' => 'Symfony\Component\Validator\Constraints\Choice',
        'Collection' => 'Symfony\Component\Validator\Constraints\Collection',
        'Collection\Optional'=> 'Symfony\Component\Validator\Constraints\Collection\Optional',
        'Collection\Required'=> 'Symfony\Component\Validator\Constraints\Collection\Required',
        'Count' => 'Symfony\Component\Validator\Constraints\Count',
        'Country' => 'Symfony\Component\Validator\Constraints\Country',
        'Currency' => 'Symfony\Component\Validator\Constraints\Currency',
        'Date' => 'Symfony\Component\Validator\Constraints\Date',
        'DateTime' => 'Symfony\Component\Validator\Constraints\DateTime',
        'Email' => 'Symfony\Component\Validator\Constraints\Email',
        'EqualTo' => 'Symfony\Component\Validator\Constraints\EqualTo',
        'Existence' => 'Symfony\Component\Validator\Constraints\Existence',
        'False' => 'Symfony\Component\Validator\Constraints\False',
        'File' => 'Symfony\Component\Validator\Constraints\File',
        'GreaterThan' => 'Symfony\Component\Validator\Constraints\GreaterThan',
        'GreaterThanOrEqual' => 'Symfony\Component\Validator\Constraints\GreaterThanOrEqual',
        'Iban' => 'Symfony\Component\Validator\Constraints\Iban',
        'IdenticalTo' => 'Symfony\Component\Validator\Constraints\IdenticalTo',
        'Image' => 'Symfony\Component\Validator\Constraints\Image',
        'Ip' => 'Symfony\Component\Validator\Constraints\Ip',
        'Isbn' => 'Symfony\Component\Validator\Constraints\Isbn',
        'Issn' => 'Symfony\Component\Validator\Constraints\Issn',
        'Language' => 'Symfony\Component\Validator\Constraints\Language',
        'Length' => 'Symfony\Component\Validator\Constraints\Length',
        'LessThan' => 'Symfony\Component\Validator\Constraints\LessThan',
        'LessThanOrEqual' => 'Symfony\Component\Validator\Constraints\LessThanOrEqual',
        'Locale' => 'Symfony\Component\Validator\Constraints\Locale',
        'Luhn' => 'Symfony\Component\Validator\Constraints\Luhn',
        'NotBlank' => 'Symfony\Component\Validator\Constraints\NotBlank',
        'NotEqualTo' => 'Symfony\Component\Validator\Constraints\NotEqualTo',
        'NotIdenticalTo' => 'Symfony\Component\Validator\Constraints\NotIdenticalTo',
        'NotNull' => 'Symfony\Component\Validator\Constraints\NotNull',
        'Null' => 'Symfony\Component\Validator\Constraints\Null',
        'Optional' => 'Symfony\Component\Validator\Constraints\Optional',
        'Range' => 'Symfony\Component\Validator\Constraints\Range',
        'Regex' => 'Symfony\Component\Validator\Constraints\Regex',
        'Required' => 'Symfony\Component\Validator\Constraints\Required',
        'Time' => 'Symfony\Component\Validator\Constraints\Time',
        'True' => 'Symfony\Component\Validator\Constraints\True',
        'Type' => 'Symfony\Component\Validator\Constraints\Type',
        'Url' => 'Symfony\Component\Validator\Constraints\Url'
    );

    /**
     * Register a new constraint
     * @param $name
     * @param $namespace
     */
    public function register($name, $namespace){
        $this->constraints[$name] = $namespace;
    }
    /**
     * Generic getter for constraint
     *
     * @param string $name
     * @param array $parameters
     * @param bool $silent
     *
     * @return Constraint
     */
    public function get($name, array $parameters=array(), $silent=false){
        if($this->has($name, $silent)) {
            return new $this->constraints[$name]($parameters);
        }
    }

    /**
     * Checks if constraint is registered
     *
     * @param string $name
     * @param bool $silent
     *
     * @throws \LogicException
     * @return boolean
     */
    public function has($name, $silent=true){
        $exist = array_key_exists($name, $this->constraints);
        if(!$silent && !$exist)
            throw new \LogicException('Constraint ['.$name.'] not registered !');
        return $exist;
    }

    public function validateValue($value, array $constraints)
    {
        return $this->container->get('validator')->validateValue($value, $constraints);
    }
}