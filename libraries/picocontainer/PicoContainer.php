<?php


/**
 * This is the core interface for PicoContainer. It is used to retrieve component instances from the container; 
 * it only has accessor methods. In order to register components in a PicoContainer, use a {@link MutablePicoContainer}.
 *
 * @author Java version authors
 * @author Pawel Kozlowski <pawel.kozlowski@gmail.com>
 * @version $Revision$
 */
interface PicoContainer {
	public function getComponentInstance($componentKey);
	public function getComponentInstanceOfType($componentType);
	public function getComponentAdapter($componentKey);
	public function getComponentAdaptersOfType($componentType);
	public function getComponentAdapterOfType($componentType);
}

/**
 * This is the core interface used for registration of components with a container. It is possible to register 
 * an implementation class, an instance or a ComponentAdapter.
 *
 * @author Java version authors
 * @author Pawel Kozlowski
 * @version $Revision$
 */
interface MutablePicoContainer extends PicoContainer {
	public function regComponent(ComponentAdapter $componentAdapter);
	public function regComponentImpl($componentKey, $componentImplementation = '', $componentParams = array ());
	public function regComponentImplWithIncFileName($includeFileName, $componentKey, $componentImplementation = '', $componentParams = array ());
	public function regComponentInstance($componentInstance, $componentKey = null);
	public function unregComponent($componentKey);
}

/**
 * The Standard {@link PicoContainer}/{@link MutablePicoContainer} implementation.
 *
 * Using Class name as keys to the {@link regComponentImpl()}
 * method makes a subtle semantic difference:
 *
 * If there are more than one registered components of the same type and one of them are
 * registered with a Class name key of the corresponding type, this component
 * will take precedence over other components during type resolution.
 * 
 * @author Java version authors
 * @author Pawel Kozlowski
 * @version $Revision$
 */
class DefaultPicoContainer implements MutablePicoContainer {
	private $_parentContainer = null;

	private $_componentAdapters = array ();
	private $_componentAdapterFactory;

	/**
	 * Handle instantiation
	 *
	 * @param null|ComponentAdapterFactory
	 *    The {@link ComponentAdapterFactory} to utilize while loading 
	 *    components.
	 *  @param null||PicoContainer parentContainer Parent container for forming container hierarchies
	 */
	public function __construct($componentAdapterFactory = null, $parentContainer = null) {
		assert('is_null($componentAdapterFactory) || 
		            $componentAdapterFactory instanceof ComponentAdapterFactory');
		if (is_null($componentAdapterFactory)) {
			$componentAdapterFactory = new CachingComponentAdapterFactory(new ConstructorInjectionComponentAdapterFactory());
		}

		$this->_componentAdapterFactory = $componentAdapterFactory;
		$this->_parentContainer = $parentContainer;
	}

	/**
	 * Returns an instance of a given component based on the provided
	 * <i>$componentKey</i>.
	 *
	 * Returns <i>null</i> if unable to find component.
	 *
	 * @see getComponentAdapter()
	 * @param string
	 * @return object|null
	 */
	public function getComponentInstance($componentKey) {
		$componentAdapter = $this->getComponentAdapter($componentKey);
		return $this->getComponentInstanceFromAdapter($componentAdapter);
	}

	/**
	 * Returns an instance of a given component based on the provided
	 * <i>$componentType</i>.
	 *
	 * Returns <i>null</i> if unable to find component.
	 *
	 * @see getComponentAdapterOfType()
	 * @param string
	 * @return object|null
	 */
	public function getComponentInstanceOfType($componentType) {
		$componentAdapter = $this->getComponentAdapterOfType($componentType);
		return $this->getComponentInstanceFromAdapter($componentAdapter);
	}

	private function getComponentInstanceFromAdapter($componentAdapter) {

		$instance = null;

		if (!is_null($componentAdapter)) {
			$instance = $componentAdapter->getComponentInstance($this);
		}

		return $instance;
	}

	/**
	 * Returns a component adapater based on the provide <i>$componentKey</i>.
	 *
	 */
	public function getComponentAdapter($componentKey) {
		if (array_key_exists($componentKey, $this->_componentAdapters)) {
			return $this->_componentAdapters[$componentKey];
		}

		if (!is_null($this->_parentContainer)) {
			return $this->_parentContainer->getComponentAdapter($componentKey);
		}

		return null;
	}

	/**
	 * Returns an array of component adapters based on the provided 
	 * <i>$componentType</i>.
	 *
	 * @param array
	 */
	public function getComponentAdaptersOfType($componentType) {
		$result = array ();
		$ctReflectionClass = new ReflectionClass($componentType);

		foreach ($this->_componentAdapters as $cadapter) {
			if ($ctReflectionClass->isInterface() && class_exists($cadapter->getComponentImplementation())) {
				$catReflectionClass = new ReflectionClass($cadapter->getComponentImplementation());
				if ($catReflectionClass->implementsInterface($ctReflectionClass->getName())) {
					$result[] = $cadapter;
				}
			} else {
				if ($componentType == $cadapter->getComponentImplementation()) {
					$result[] = $cadapter;
				} else {
					if (class_exists($cadapter->getComponentImplementation())) {
						$catReflectionClass = new ReflectionClass($cadapter->getComponentImplementation());
						if ($catReflectionClass->isSubclassOf($ctReflectionClass)) {
							$result[] = $cadapter;
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Returns a specific component adapter based on the type
	 *
	 * If multiple component adapters are found, this will throw an
	 * {@link AmbiguousComponentResolutionException}.
	 *
	 * @param string
	 * @param object|null
	 */
	public function getComponentAdapterOfType($expectedType) {
		$adapterByKey = $this->getComponentAdapter($expectedType);
		if (!is_null($adapterByKey)) {
			return $adapterByKey;
		}

		$result = $this->getComponentAdaptersOfType($expectedType);

		if (count($result) == 1) {
			return $result[0];
		}
		elseif (count($result) == 0) {
			if (!is_null($this->_parentContainer)) {
				return $this->_parentContainer->getComponentAdapterOfType($expectedType);
			}

			return null;
		} else {
			$result_to_ex = array ();
			foreach ($result as $cadapter) {
				$result_to_ex[] = $cadapter->getComponentKey();
			}
			throw new AmbiguousComponentResolutionException($expectedType, $result_to_ex);
		}
	}

	/**
	 * Register a new component adapter
	 *
	 * @see ComponentAdapter
	 * @param ComponentAdapter
	 */
	public function regComponent(ComponentAdapter $componentAdapter) {				

		if (array_key_exists($componentAdapter->getComponentKey(), $this->_componentAdapters)) {
			throw new DuplicateComponentKeyRegistrationException($componentAdapter->getComponentKey());
		}
		$this->_componentAdapters[$componentAdapter->getComponentKey()] = $componentAdapter;
	}

	/**
	 * Register a new component implementation
	 *
	 * @see regComponent()
	 * @param string
	 * @param string
	 * @param array
	 */
	public function regComponentImpl($componentKey, $componentImplementation = '', $componentParams = array ()) {
		$ca = $this->_createComponentAdapter($componentKey, $componentImplementation, $componentParams);
		$this->regComponent($ca);
	}

	/**
	 * Register a new component implementation for lazy loading
	 *
	 * @see regComponent()
	 * @param string
	 * @param string
	 * @param string
	 * @param array
	 */
	public function regComponentImplWithIncFileName($includeFileName, $componentKey, $componentImplementation = '', $componentParams = array ()) {
		if (empty ($includeFileName)) {
			throw new IncludeFileNameNotDefinedRegistrationException();
		}

		$ca = $this->_createComponentAdapter($componentKey, $componentImplementation, $componentParams);
		$this->regComponent(new LazyIncludingComponentAdapter($ca, $includeFileName));
	}

	/**
	 * Register a specific instance of a class
	 *
	 * @param object
	 * @param string|null
	 */
	public function regComponentInstance($componentInstance, $componentKey = null) {
		if (!is_object($componentInstance)) {
			throw new PicoRegistrationException('$componentInstance is not an object');
		}
		$this->regComponent(new InstanceComponentAdapter($componentInstance, $componentKey));
	}

	/**
	 * Removes a registered component based on the provided 
	 * <i>$componentKey</i>.
	 *
	 * @param string
	 */
	public function unregComponent($componentKey) {
		if (isset ($this->_componentAdapters[$componentKey])) {
			unset ($this->_componentAdapters[$componentKey]);
		}
	}

	/**
	 * Used to create a component adapter using the internal 
	 * {@link $_componentAdapterFactory}.
	 *
	 * @param string
	 * @param string
	 * @param array
	 * @return ComponentAdapter
	 */
	private function _createComponentAdapter($key, $implementation, $params) {		
		return $this->_componentAdapterFactory->createComponentAdapter($key, $implementation, $params);
	}
}
?>