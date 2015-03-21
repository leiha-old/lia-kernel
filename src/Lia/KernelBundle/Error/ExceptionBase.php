<?php

namespace Lia\KernelBundle\Error;

abstract class ExceptionBase
    extends \Exception
{
    /**
     * @var array
     */
    private $vars = array();

    /**
     * Allows get the category of context
     * @return string
     */
    abstract public function getCategoryOfContext();

    /**
     * @param string $message
     * @param array $vars
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $message = "", array $vars = array(), $code = 0, \Exception $previous = null
    ){
        $this->vars = $vars;
        $message    = '['.$this->getCategoryOfContext().'] : '.(
            count($vars)
                ? $this->parse($message)
                : $message
        );
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $message
     * @return string
     */
    protected function parse($message){
        return preg_replace_callback('~\[:(.[^\]]*)]~',
            function($matches) {
                $explode = explode(':', $matches[1]);
                return count($explode) == 1
                    ? $this->getMappedVars($matches[1])
                    : $this->callFunction($explode, $matches[1])
                    ;
            },
            $message
        );
    }

    /**
     * @param string $string
     * @return string
     */
    protected function getMappedVars($string){
        return isset($this->vars[$string])
            ? $this->vars[$string]
            : $string
            ;
    }

    /**
     * @param array  $explode
     * @param string $original
     * @return string
     */
    protected function callFunction(array $explode, $original){
        $function = $this->checkAndFormatForFunction(
            array_shift($explode),
            $explode
        );
        return function_exists($function)
            ? call_user_func_array($function, $explode)
            : $original
            ;
    }

    /**
     * @param string $function
     * @param array  $args
     * @return string
     */
    protected function checkAndFormatForFunction($function, array &$args)
    {
        switch($function){
            case 'implode' :
                $args[1] = $this->getMappedVars($args[1]);
                break;
            default :
                $args[0] = $this->getMappedVars($args[0]);
                break;
        }
        return $function;
    }
}