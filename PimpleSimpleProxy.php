<?php

namespace Kadevland\PimpleProxy;

/**
 * Class PimpleSimpleProxy
 * @package Kadevland\PimpleProxy
 * @author ...
 */
class PimpleSimpleProxy
{
    /**
     * @var [type] $container [desc]
     */
    protected $container;

    /**
     * @var string $proxyName [desc]
     */
    protected $proxyName = '';

    /**
     * @var string $proxyNamespace [desc]
     */
    protected $proxyNamespace = '';

    /**
     * @var mixed|string $prefixClass [desc]
     */
    protected $prefixClass = '';

    /**
     * @var mixed|string $suffixClass [desc]
     */
    protected $suffixClass = '';

    /**
     * PimpleSimpleProxy constructor.
     * @param [type] $container [desc]
     * @param [type] $proxyName [desc]
     * @param string $proxyNamespace [desc]
     * @param array $option [desc]
     */
    public function __construct($container, $proxyName, $proxyNamespace = '', $option = array())
    {
        $this->container = $container;
        $this->proxyName = $proxyName;

        if(!empty($proxyNamespace)) {
            $this->proxyNamespace = '\\'.trim($proxyNamespace, '\\');
        }

        if(isset($option['prefix'])) {
            $this->prefixClass = $option['prefix'];
        }

        if(isset($option['suffix'])) {
            $this->suffixClass = $option['suffix'];
        }
    }

    /**
     * [Description]
     * @param $class
     * @return mixed
     */
    public  function __get($class)
    {
        $classNameContainer = $this->getNameContainer($class);

        if(!isset($this->container[$classNameContainer])) {
            $this->attachClass($classNameContainer, $class);
        }

        return $this->container[$classNameContainer];
    }

    /**
     * [Description]
     * @param $class
     * @return string
     */
    protected function getClassName($class)
    {
        return $this->concatCamelCase(array($this->prefixClass, $class, $this->suffixClass));
    }

    /**
     * [Description]
     * @param $class
     * @return string
     */
    protected function getNameContainer($class)
    {
        return $this->concatCamelCase(array($this->proxyName, $class));
    }

    /**
     * [Description]
     * @param $nameContainer
     * @param $class
     */
    protected function attachClass($nameContainer, $class)
    {
        $classPath = $this->getClassPath($class);

        $this->container[$nameContainer] = function($container) use($classPath) {
            return new $classPath($container);
        };
    }

    /**
     * [Description]
     * @param $class
     * @return string
     */
    protected function getClassPath($class)
    {
        return	$this->proxyNamespace.'\\'.$this->getClassName($class);
    }

    /**
     * [Description]
     * @param array $array
     * @return string
     */
    protected function concatCamelCase($array = array())
    {
        $array = array_map(function($val) {
            return ucfirst(trim($val)) ;
        }, $array);

        return implode('',$array);
    }

    /**
     * [Description]
     */
    public function register()
    {
        if(!empty($this->proxyName)) {
            $this->container[$this->proxyName] = $this;
        }
    }
}
