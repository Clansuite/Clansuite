<?php
require_once(dirname(__FILE__) . '/locator.php');

class Phemto {
    private $registry = array();

    function register($service) {
        if (! $service instanceof PhemtoLocator) {
            $service = new Locator($service);
        }
        $this->registerLocator($service);
    }

    private function registerLocator($locator) {
        $interfaces = $locator->getInterfaces();
        foreach ($interfaces as $interface) {
            $this->registry[$interface] = $locator;
        }
    }

    function instantiate($interface, $parameters = array()) {
        if (! isset($this->registry[$interface])) {
            throw new Exception("No class registered for interface $interface");
        }
        $locator = $this->registry[$interface];
        $dependencies = $this->instantiateDependencies(
        		$locator->getReflection(),
        		$locator->getParameters($parameters));
        return $locator->instantiate($dependencies);
    }

    private function instantiateDependencies($reflection, $supplied) {
    	$dependencies = array();
        if ($constructor = $reflection->getConstructor()) {
            foreach ($constructor->getParameters() as $parameter) {
            	if ($interface = $parameter->getClass()) {
            		$dependencies[] = $this->instantiate($interface->getName());
            	} elseif ($dependency = array_shift($supplied)) {
            		$dependencies[] = $dependency;
            	}
            }
        }
        return $dependencies;
    }
}
?>