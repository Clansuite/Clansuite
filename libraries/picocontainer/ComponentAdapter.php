<?php


/**
 * A component adapter is responsible for providing a specific component instance. An instance of an implementation of
 * this interface is used inside a {@link PicoContainer} for every registered component or instance.  Each
 * <code>ComponentAdapter</code> instance has to have a key which is unique within that container. 
 *
 * @author Java version authors
 * @author Pawel Kozlowski <pawel.kozlowski@gmail.com>
 * @version $Revision$
 */
interface ComponentAdapter {
	/**
	 * Retrieve the key associated with the component.
	 * 
	 * @return string 
	 *    The component's key.
	 */
	public function getComponentKey();

	/**
	 * Retrieve the class name of the component.
	 * 
	 * @return object
	 *    The component's implementation class. Should normally be a concrete
	 *    class (ie, a class that can be instantiated).
	 */
	public function getComponentImplementation();

	/**
	 * Retrieve the component instance. 
	 *
	 * This method will usually create a new instance each time it is called,
	 * but that is not required. For example, {@link CachingComponentAdapter} 
	 * will always return the same instance.
	 * 
	 * @param PicoContainer 
	 *    The {@link PicoContainer} that is used to resolve any possible 
	 *    dependencies of the instance.
	 * @return object
	 *    The component instance.
	 */
	public function getComponentInstance(PicoContainer $container);
}

interface ComponentAdapterFactory {
	public function createComponentAdapter($componentKey, $componentImplementation, $parameters);
}

class ConstructorInjectionComponentAdapterFactory implements ComponentAdapterFactory {
	public function createComponentAdapter($componentKey, $componentImplementation, $parameters) {
		return new ConstructorInjectionComponentAdapter($componentKey, $componentImplementation, $parameters);
	}
}

class SetterInjectionComponentAdapterFactory implements ComponentAdapterFactory {
	public function createComponentAdapter($componentKey, $componentImplementation, $parameters) {
		return new SetterInjectionComponentAdapter($componentKey, $componentImplementation, $parameters);
	}
}

class DecoratingComponentAdapterFactory implements ComponentAdapterFactory {
	private $_delegate;

	public function __construct(ComponentAdapterFactory $delegate) {
		$this->_delegate = $delegate;
	}

	public function createComponentAdapter($componentKey, $componentImplementation, $parameters) {
		return $this->_delegate->createComponentAdapter($componentKey, $componentImplementation, $parameters);
	}
}

class CachingComponentAdapterFactory extends DecoratingComponentAdapterFactory {

	public function createComponentAdapter($componentKey, $componentImplementation, $parameters) {
		return new CachingComponentAdapter(parent :: createComponentAdapter($componentKey, $componentImplementation, $parameters));
	}
}

abstract class AbstractComponentAdapter implements ComponentAdapter {
	private $_componentKey;
	private $_componentImplementation;

	public function __construct($componentKey, $componentImplementation = null, $componentParams = array ()) {
		if ($componentImplementation == null) {
			$componentImplementation = $componentKey;
		}

		$this->_componentKey = $componentKey;
		$this->_componentImplementation = $componentImplementation;

	}

	public function getComponentKey() {
		return $this->_componentKey;
	}

	public function getComponentImplementation() {
		return $this->_componentImplementation;
	}
}

abstract class InstantiatingComponentAdapter extends AbstractComponentAdapter {
	private $_componentParams;

	public function __construct($componentKey, $componentImplementation = null, $componentParams = array ()) {
		parent :: __construct($componentKey, $componentImplementation);
		if (is_array($componentParams)) {
			foreach ($componentParams as $paramKey => $paramObj) {
				if (!($paramObj instanceof Parameter)) {
					$componentParams[$paramKey] = new ConstantParameter($paramObj);
				}
			}
			$this->_componentParams = $componentParams;
		} else {
			//TODO: throw exception or allow parameters registration without array, assuming that tere is only one param??? 
			$this->_componentParams = array ();
		}
	}

	/**
	 * getComponentParams()
	 *
	 * @return ??
	 */
	public function getComponentParams() {
		return $this->_componentParams;
	}

	/**
	 * getArgumentsParameters
	 *
	 * @param PicoContainer
	 * @param ReflectionMethod
	 * @return ??
	 */
	protected function getArgumentsParameters(PicoContainer $container, ReflectionMethod $rm) {

		$param_to_pass_to_constr = array ();
		$instances_to_pass_to_constr = array ();

		$params = $this->getComponentParams();
		$constr_all_params = $rm->getParameters();		

		if (is_array($constr_all_params) && count($constr_all_params)) {

			$paramNo = 0;
			foreach ($constr_all_params as $k => $v) {

				$instances_to_pass_to_constr[$k] = null;

				if (array_key_exists($v->getName(), $params) && $params[$v->getName()] != null) {
					//supplied param - hint by name                        
					$param_to_pass_to_constr[$k] = $params[$v->getName()];
					$instances_to_pass_to_constr[$k] = $param_to_pass_to_constr[$k]->resolveInstance($container, $this, $v->getClass());
					
				} elseif (array_key_exists($paramNo, $params) && $params[$paramNo] != null) {
					//supplied param - hint by param no
					$param_to_pass_to_constr[$k] = $params[$paramNo];
					$instances_to_pass_to_constr[$k] = $param_to_pass_to_constr[$k]->resolveInstance($container, $this, $v->getClass());					
				} else {

					$param_rc = null;
					$param_rc_name = '';

					try {
						$param_rc = $v->getClass();
					} catch (ReflectionException $e) {
						//TODO: dirty hack, please have a look at [URL Bug Report]                    		
						$matches = array ();
						preg_match("/Class (.+) does not/", $e->getMessage(), $matches);
						$param_rc_name = $matches[1];

					}

					if ($param_rc != null) {
						$param_to_pass_to_constr[$k] = new BasicComponentParameter();
						$instances_to_pass_to_constr[$k] = $param_to_pass_to_constr[$k]->resolveInstance($container, $this, $param_rc->getName());
					}
					elseif ($param_rc_name != '') {
						$param_to_pass_to_constr[$k] = new BasicComponentParameter($param_rc_name);
						$instances_to_pass_to_constr[$k] = $param_to_pass_to_constr[$k]->resolveInstance($container, $this, $param_rc_name);
					}
					elseif ($v->isDefaultValueAvailable()) {
						$param_to_pass_to_constr[$k] = new ConstantParameter($v->getDefaultValue());
						$instances_to_pass_to_constr[$k] = $param_to_pass_to_constr[$k]->resolveInstance($container, $this, null);
					}
				}
				
				$paramNo++;

				if (is_null($instances_to_pass_to_constr[$k]) || (!is_object($instances_to_pass_to_constr[$k]) && $instances_to_pass_to_constr[$k] == '')) {
					throw new UnsatisfiableDependenciesException($this, array ($v->getName()));
				}
			}
		}

		return $instances_to_pass_to_constr;
	}

	/**
	 * newInstance()
	 *
	 * @todo Document
	 */
	protected function newInstance($args_to_pass_to_constr = array ()) {
		$to_eval_args = array ();
		$to_eval_params_str = '';

		if (is_array($args_to_pass_to_constr) && count($args_to_pass_to_constr)) {
			foreach ($args_to_pass_to_constr as $k => $v) {
				$to_eval_params[] = '$args_to_pass_to_constr['.$k.']';
			}

			$to_eval_params_str = join(', ', $to_eval_params);
		}

		$objectclass_to_create = $this->getComponentImplementation();
		$to_eval_str = '$rv = new $objectclass_to_create('.$to_eval_params_str.');';

		eval ($to_eval_str);

		return $rv;
	}
}

class SetterInjectionComponentAdapter extends InstantiatingComponentAdapter {
	private $instantiationGuard = false;

	public function getComponentInstance(PicoContainer $container) {

		if ($this->instantiationGuard) {
			throw new CyclicDependencyException();
		} else {
			$this->instantiationGuard = true;

			$rc = new ReflectionClass($this->getComponentImplementation());
			$reflection_constr = $rc->getConstructor();

			//TODO: Is this enough for checking sub-class constructors???
			if ($reflection_constr == null) {
				$rv = $this->newInstance();

				$setter_methods = array ();
				$rm = $rc->getMethods();

				if (is_array($rm)) {
					foreach ($rm as $refMethod) {
						if ($refMethod->isPublic() && $this->isSetter($refMethod)) {
							$instance_arg = $this->getArgumentsParameters($container, $refMethod);
							$method_name = $refMethod->getName();
							$rv-> $method_name ($instance_arg[0]);
						}
					}
				}
			} else {
				throw new Exception('We only support default constructors!');
			}

			$this->instantiationGuard = false;
			return $rv;
		}
	}

	private function isSetter(ReflectionMethod $rm) {
		$mname = $rm->getName();

		if (strlen($mname) > 3 && substr($mname, 0, 3) == 'set' && strtoupper(substr($mname, 3, 1)) == substr($mname, 3, 1) && count($rm->getParameters()) == 1) {
			return true;
		} else {
			return false;
		}
	}
}

class ConstructorInjectionComponentAdapter extends InstantiatingComponentAdapter {
	private $instantiationGuard = false;

	public function getComponentInstance(PicoContainer $container) {

		if ($this->instantiationGuard) {
			throw new CyclicDependencyException();
		} else {
			$this->instantiationGuard = true;

			$rc = new ReflectionClass($this->getComponentImplementation());

			$reflection_constr = $rc->getConstructor();
			$args_to_pass_to_constr = array ();
			if ($reflection_constr != null) {
				$args_to_pass_to_constr = $this->getArgumentsParameters($container, $reflection_constr);
			}

			$rv = $this->newInstance($args_to_pass_to_constr);

			$this->instantiationGuard = false;

			return $rv;
		}
	}
}

class InstanceComponentAdapter extends AbstractComponentAdapter {
	private $_componentInstance;

	public function __construct($componentInstance, $componentKey = null) {
		if ($componentKey == null) {
			$componentKey = get_class($componentInstance);
		}

		parent :: __construct($componentKey, get_class($componentInstance));
		$this->_componentInstance = $componentInstance;
	}

	public function getComponentInstance(PicoContainer $container) {
		return $this->_componentInstance;
	}
}

class DecoratingComponentAdapter implements ComponentAdapter {
	private $_delegate;

	public function __construct($delegate) {
		$this->_delegate = $delegate;
	}

	public function getComponentKey() {
		return $this->_delegate->getComponentKey();
	}
	public function getComponentImplementation() {
		return $this->_delegate->getComponentImplementation();
	}
	public function getComponentInstance(PicoContainer $container) {
		return $this->getDelegate()->getComponentInstance($container);
	}

	public function getDelegate() {
		return $this->_delegate;
	}

}

class LazyIncludingComponentAdapter extends DecoratingComponentAdapter {
	private $includeFileName;

	public function __construct($delegate, $includeFileName) {
		$this->includeFileName = $includeFileName;
		parent :: __construct($delegate);
	}

	public function getComponentInstance(PicoContainer $container) {
		require_once ($this->includeFileName);

		if (class_exists($this->getDelegate()->getComponentImplementation())) {
			return parent :: getComponentInstance($container);
		} else {
			throw new LazyIncludedClassNotDefinedException($this->getDelegate()->getComponentImplementation());
		}
	}
}

class CachingComponentAdapter extends DecoratingComponentAdapter {
	private $_instanceReference;

	public function getComponentInstance(PicoContainer $container) {
		if ($this->_instanceReference == null) {
			$component = $this->getDelegate()->getComponentInstance($container);
			$this->_instanceReference = $component;
		} else {
			$component = $this->_instanceReference;
		}

		return $component;
	}
}
?>