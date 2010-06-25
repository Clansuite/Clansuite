<?php
require_once(dirname(__FILE__) . '/lifecycle.php');
require_once(dirname(__FILE__) . '/repository.php');

class CannotFindImplementation extends Exception { }
class CannotDetermineImplementation extends Exception { }
class SetterDoesNotExist extends Exception { }
class MissingDependency extends Exception { }

class Phemto {
    private $top;
    private $named_parameters = array();
    private $unnamed_parameters = array();

    function __construct() {
        $this->top = new Context($this);
    }

    function willUse($preference) {
        $this->top->willUse($preference);
    }
    
    function register($preference) {
        $this->top->willUse($preference);
    }

    function forVariable($name) {
        return $this->top->forVariable($name);
    }

    function whenCreating($type) {
        return $this->top->whenCreating($type);
    }

    function forType($type) {
        return $this->top->forType($type);
    }

    function fill() {
        $names = func_get_args();
        return new IncomingParameters($names, $this);
    }

    function with() {
        $values = func_get_args();
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);
        return $this;
    }

    function create() {
        $values = func_get_args();
        $type = array_shift($values);
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);
        $this->repository = new ClassRepository();
        $object = $this->top->create($type);
        $this->named_parameters = array();
        return $object;
    }
    
    function instantiate() {
        $values = func_get_args();
        $type = array_shift($values);
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);
        $this->repository = new ClassRepository();
        $object = $this->top->create($type);
        $this->named_parameters = array();
        return $object;
    }

    function pickFactory($type, $candidates) {
        throw new CannotDetermineImplementation($type);
    }

    function settersFor($class) {
        return array();
    }

    function wrappersFor($type) {
        return array();
    }

    function useParameters($parameters) {
        $this->named_parameters = array_merge($this->named_parameters, $parameters);
    }

    function instantiateParameter($parameter, $nesting) {
        if (isset($this->named_parameters[$parameter->getName()])) {
            return $this->named_parameters[$parameter->getName()];
        }
        if ($value = array_shift($this->unnamed_parameters)) {
            return $value;
        }
        throw new MissingDependency($parameter->getName());
    }

    function repository() {
        return $this->repository;
    }
}

class IncomingParameters {
    private $injector;

    function __construct($names, $injector) {
        $this->names = $names;
        $this->injector = $injector;
    }

    function with() {
        $values = func_get_args();
        $this->injector->useParameters(array_combine($this->names, $values));
        return $this->injector;
    }
}

class Context {
    private $parent;
    private $repository;
    private $registry = array();
    private $variables = array();
    private $contexts = array();
    private $types = array();
    private $wrappers = array();

    function __construct($parent) {
        $this->parent = $parent;
    }

    function willUse($preference) {
        if ($preference instanceof Lifecycle) {
            $lifecycle = $preference;
        } elseif (is_object($preference)) {
            $lifecycle = new Value($preference);
        } else {
            $lifecycle = new Factory($preference);
        }
        array_unshift($this->registry, $lifecycle);
    }

    function forVariable($name) {
        return $this->variables[$name] = new Variable($this);
    }

    function whenCreating($type) {
        if (! isset($this->contexts[$type])) {
            $this->contexts[$type] = new Context($this);
        }
        return $this->contexts[$type];
    }

    function forType($type) {
        if (! isset($this->types[$type])) {
            $this->types[$type] = new Type();
        }
        return $this->types[$type];
    }

    function wrapWith($type) {
        array_push($this->wrappers, $type);
    }

    function create($type, $nesting = array()) {
        $lifecycle = $this->pickFactory($type, $this->repository()->candidatesFor($type));
        $context = $this->determineContext($lifecycle->class);
        if ($wrapper = $context->hasWrapper($type, $nesting)) {
            return $this->create($wrapper, $this->cons($wrapper, $nesting));
        }
        $instance = $lifecycle->instantiate($context->createDependencies(
                        $this->repository()->getConstructorParameters($lifecycle->class),
                        $this->cons($lifecycle->class, $nesting)));
        $this->invokeSetters($context, $nesting, $lifecycle->class, $instance);
        return $instance;
    }

    function pickFactory($type, $candidates) {
        if (count($candidates) == 0) {
            throw new CannotFindImplementation($type);
        } elseif ($preference = $this->preferFrom($candidates)) {
            return $preference;
        } elseif (count($candidates) == 1) {
            return new Factory($candidates[0]);
        } else {
            return $this->parent->pickFactory($type, $candidates);
        }
    }

    function hasWrapper($type, $already_applied) {
        foreach ($this->wrappersFor($type) as $wrapper) {
            if (! in_array($wrapper, $already_applied)) {
                return $wrapper;
            }
        }
        return false;
    }
    
    private function invokeSetters($context, $nesting, $class, $instance) {
        foreach ($context->settersFor($class) as $setter) {
            $context->invoke($instance, $setter, $context->createDependencies(
                                $this->repository()->getParameters($class, $setter),
                                $this->cons($class, $nesting)));
        }
    }

    private function settersFor($class) {
        $setters = isset($this->types[$class]) ? $this->types[$class]->setters : array();
        return array_values(array_unique(array_merge(
                    $setters, $this->parent->settersFor($class))));
    }

    function wrappersFor($type) {
        return array_values(array_merge(
                    $this->wrappers, $this->parent->wrappersFor($type)));
    }

    function createDependencies($parameters, $nesting) {
        $values = array();
        foreach ($parameters as $parameter) {
            try {
                $values[] = $this->instantiateParameter($parameter, $nesting);
            } catch (Exception $e) {
                if ($parameter->isOptional()) {
                    break;
                }
                throw $e;
            }
        }
        return $values;
    }

    private function instantiateParameter($parameter, $nesting) {
        if ($hint = $parameter->getClass()) {
            return $this->create($hint->getName(), $nesting);
        } elseif (isset($this->variables[$parameter->getName()])) {
            if ($this->variables[$parameter->getName()]->preference instanceof Lifecycle) {
                return $this->variables[$parameter->getName()]->preference->instantiate(array());
            } elseif (! is_string($this->variables[$parameter->getName()]->preference)) {
                return $this->variables[$parameter->getName()]->preference;
            }
            return $this->create($this->variables[$parameter->getName()]->preference, $nesting);
        }
        return $this->parent->instantiateParameter($parameter, $nesting);
    }

    private function determineContext($class) {
        foreach ($this->contexts as $type => $context) {
            if ($this->repository()->isSupertype($class, $type)) {
                return $context;
            }
        }
        return $this;
    }

    private function invoke($instance, $method, $arguments) {
        call_user_func_array(array($instance, $method), $arguments);
    }

    private function preferFrom($candidates) {
        foreach ($this->registry as $preference) {
            if ($preference->isOneOf($candidates)) {
                return $preference;
            }
        }
        return false;
    }

    private function cons($head, $tail) {
        array_unshift($tail, $head);
        return $tail;
    }

    function repository() {
        return $this->parent->repository();
    }
}

class Variable {
    public $preference;
    private $context;
    
    function __construct($context) {
        $this->context = $context;
    }

    function willUse($preference) {
        $this->preference = $preference;
        return $this->context;
    }
    
    function useString($string) {
        $this->preference = new Value($string);
        return $this->context;
    }
}

class Type {
    public $setters = array();

    function call($method) {
        array_unshift($this->setters, $method);
    }
}
?>