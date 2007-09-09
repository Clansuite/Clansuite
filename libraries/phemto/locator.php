<?php
interface PhemtoLocator {
	function getReflection();
	function getInterfaces();
	function getParameters($parameters);
	function instantiate($dependencies);
}

class Locator implements PhemtoLocator {
    private $class;

	function __construct($class) {
		$this->class = $class;
	}
	
	function getReflection() {
		return new ReflectionClass($this->class);
	}

	function getInterfaces() {
		return Locator::discoverInterfaces($this->class);
	}

	function instantiate($dependencies) {
		return call_user_func_array(
				array(new ReflectionClass($this->class), 'newInstance'),
				$dependencies);
	}

	function getParameters($parameters) {
		return $parameters;
	}

    static function discoverInterfaces($class) {
		if (! class_exists($class)) {
			throw new Exception("Cannot inject dependency for missing class $class");
		}
        return array_merge(array($class), class_implements($class), class_parents($class));
    }
}

class Singleton implements PhemtoLocator {
    private $class;
	private $parameters;
	private $registry = array();

	function __construct($class, $parameters = array()) {
		$this->class = $class;
		$this->parameters = $parameters;
	}
	
	function getReflection() {
		return new ReflectionClass($this->class);
	}

	function getInterfaces() {
		return Locator::discoverInterfaces($this->class);
	}

	function instantiate($dependencies) {
		if (! isset($this->registry[$this->class])) {
			$this->registry[$this->class] = call_user_func_array(
					array(new ReflectionClass($this->class), 'newInstance'),
					$dependencies);
		}
		return $this->registry[$this->class];
	}

	function getParameters($parameters) {
		return $this->parameters;
	}

	static function clear() {
		self::$registry = array();
	}
}

class LocatorDecorator {
    private $locator;

    function __construct($service) {
        if (! $service instanceof PhemtoLocator) {
            $service = new Locator($service);
        }
        $this->locator = $service;
    }

    function instantiate($dependencies) {
        return $this->locator->instantiate($dependencies);
    }

	function getReflection() {
		return $this->locator->getReflection();
	}

	function getInterfaces() {
		return $this->locator->getInterfaces();
	}

	function getParameters($parameters) {
		return $this->locator->getParameters($parameters);
	}
}
?>