<?php

namespace Kadevland\PimpleProxy;



class PimpleSimpleProxy {


	protected $container;
	
	protected $proxyName='';

	protected $proxyNamespace='';

	protected $prefixClass='';

	protected $suffixClass='';


	public function __construct($container,$proxyName,$proxyNamespace='',$option=array()){
		
		$this->container=$container;
		
		
		$this->proxyName=$proxyName;
		
	
		
		if(!empty($proxyNamespace)){
		$this->proxyNamespace='\\'.trim($proxyNamespace,'\\');
		
		}

		if(isset($option['prefix'])){
			$this->prefixClass=$option['prefix'];

		}
		if(isset($option['suffix'])){

			$this->suffixClass=$option['suffix'];
		}
	}


	public  function __get($class){

		$classNameContainer=$this->getNameContainer($class);

		if(!isset($this->container[$classNameContainer])){
			
			$this->attachClass($classNameContainer,$class);

		}

		return $this->container[$classNameContainer];

	}

	protected function getClassName($class){

		return $this->concatCamelCase(array($this->prefixClass,$class,$this->suffixClass));

	}

	protected function getNameContainer($class){

		return $this->concatCamelCase(array($this->proxyName,$class));
	}
	
	protected function attachClass($nameContainer,$class){
		$classPath=$this->getClassPath($class);		
		
		$this->container[$nameContainer]=function($container) use($classPath){
				return new $classPath($container);
			};		
	}
	
	protected function getClassPath($class){
		
		return	$this->proxyNamespace.'\\'.(string)$this->getClassName($class);

	}

	protected function concatCamelCase($array=array()){

		$array=array_map(function($val){return ucfirst(trim($val)) ;},$array);
		
		return implode('',$array);		

	}

	public function register(){
		if(!empty($this->proxyName)){
			$this->container[$this->proxyName]=$this;
		}
	}





}
