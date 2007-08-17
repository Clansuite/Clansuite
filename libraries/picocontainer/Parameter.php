<?php


/**
 * This class provides control over the arguments that will be passed to a constructor. It can be used for finer control
 * over what arguments are passed to a particular constructor.
 *
 * @author Java version authors
 * @author Pawel Kozlowski <pawel.kozlowski@gmail.com>
 * @version $Revision$
 */
interface Parameter {
   /**
     * Retrieve the object from the Parameter that statisfies the expected type.
     *
     * @param PicoContainer 
     *    The {@link PicoContainer} from which dependencies are resolved.
     * @param ComponentAdapter
     *    The {@link ComponentAdapter} that is asking for the instance
     * @param expectedType  the type that the returned instance needs to match.
     * @return the instance or <code>null</code> if no suitable instance can be found.
     */
   public function resolveInstance(PicoContainer $container, ComponentAdapter $adapter, $expectedType);
}

/**
 * A ConstantParameter should be used to pass in "constant" arguments to
 * constructors.
 *
 * This includes Strings, integers or any other object that is NOT registered 
 * in the container.
 */
class ConstantParameter implements Parameter {
   private $_value;

   public function __construct($value) {
      $this->_value = $value;
   }

   public function resolveInstance(PicoContainer $container, ComponentAdapter $adapter, $expectedType) {
      return $this->_value;
   }
}

/**
 * A {@link BasicComponentParameter} should be used to pass in a particular 
 * component as an argument to a different component's constructor.
 *
 * This is particularly useful in cases where several components of the same 
 * type have been registered, but with a different key. Passing a 
 * {@link ComponentParameter} as a parameter when registering a component will 
 * give {@link PicoContainer} a hint about what other component to use in the 
 * constructor.
 */
class BasicComponentParameter implements Parameter {
   private $_componentKey;

   public function __construct($componentKey = null) {
      $this->_componentKey = $componentKey;
   }

   public function resolveInstance(PicoContainer $container, ComponentAdapter $adapter, $expectedType) {

      $adapter = $this->getTargetAdapter($container, $expectedType);

      if ($adapter != null) {
         return $adapter->getComponentInstance($container);
      } else {
         return null;
      }
   }

   private function getTargetAdapter(PicoContainer $container, $expectedType) {
      if ($this->_componentKey != null) {
         return $container->getComponentAdapter($this->_componentKey);
      } else {
         return $container->getComponentAdapterOfType($expectedType);
      }
   }
}

class CollectiveTypeParameter implements Parameter {

   private $_componentKey;

   public function __construct($componentKey = null) {
      $this->_componentKey = $componentKey;
   }
   
   public function resolveInstance(PicoContainer $container, ComponentAdapter $adapter, $expectedType) {
   	   	
   	$resultFromContainer = $container->getComponentAdaptersOfType($this->_componentKey);
   	
   	if (is_array($resultFromContainer)){
   		
   		$result = array();   		
   		for ($index = 0; $index < sizeof($resultFromContainer); $index++) {
         	$result[] = $resultFromContainer[$index]->getComponentInstance($container);
         }
         
         return $result;
   	}
   	
   	return null;
   }
}
?>