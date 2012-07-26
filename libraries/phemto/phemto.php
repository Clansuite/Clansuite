<?php
require_once __DIR__ . '/lifecycle.php';
require_once __DIR__ . '/repository.php';

class CannotFindImplementation extends \Exception
{

}

class CannotDetermineImplementation extends \Exception
{

}

class SetterDoesNotExist extends \Exception
{

}

class MissingDependency extends \Exception
{

}

class Phemto
{
    private $top;
    private $named_parameters = array();
    private $unnamed_parameters = array();

    public function __construct()
    {
        $this->top = new Context($this);
    }

    public function willUse($preference)
    {
        $this->top->willUse($preference);
    }

    public function register($preference)
    {
        $this->top->willUse($preference);
    }

    public function forVariable($name)
    {
        return $this->top->forVariable($name);
    }

    public function whenCreating($type)
    {
        return $this->top->whenCreating($type);
    }

    public function forType($type)
    {
        return $this->top->forType($type);
    }

    public function fill()
    {
        $names = func_get_args();

        return new IncomingParameters($names, $this);
    }

    public function with()
    {
        $values = func_get_args();
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);

        return $this;
    }

    public function create()
    {
        $values = func_get_args();
        $type = array_shift($values);
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);
        $this->repository = new ClassRepository();
        $object = $this->top->create($type);
        $this->named_parameters = array();

        return $object;
    }

    public function instantiate()
    {
        $values = func_get_args();
        $type = array_shift($values);
        $this->unnamed_parameters = array_merge($this->unnamed_parameters, $values);
        $this->repository = new ClassRepository();
        $object = $this->top->create($type);
        $this->named_parameters = array();

        return $object;
    }

    public function pickFactory($type, $candidates)
    {
        throw new CannotDetermineImplementation($type);
    }

    public function settersFor($class)
    {
        return array();
    }

    public function wrappersFor($type)
    {
        return array();
    }

    public function useParameters($parameters)
    {
        $this->named_parameters = array_merge($this->named_parameters, $parameters);
    }

    public function instantiateParameter($parameter, $nesting)
    {
        if (true === isset($this->named_parameters[$parameter->getName()])) {
            return $this->named_parameters[$parameter->getName()];
        }

        $value = array();
        $value = array_shift($this->unnamed_parameters);
        if ($value) {
            return $value;
        }

        throw new MissingDependency($parameter->getName());
    }

    public function repository()
    {
        return $this->repository;
    }

}

class IncomingParameters
{
    private $injector;

    public function __construct($names, $injector)
    {
        $this->names = $names;
        $this->injector = $injector;
    }

    public function with()
    {
        $values = func_get_args();
        $this->injector->useParameters(array_combine($this->names, $values));

        return $this->injector;
    }

}

class Context
{
    private $parent;
    private $repository;
    private $registry = array();
    private $variables = array();
    private $contexts = array();
    private $types = array();
    private $wrappers = array();

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function willUse($preference)
    {
        if ($preference instanceof Lifecycle) {
            $lifecycle = $preference;
        } elseif (true === is_object($preference)) {
            $lifecycle = new Value($preference);
        } else {
            $lifecycle = new Factory($preference);
        }
        array_unshift($this->registry, $lifecycle);
    }

    public function forVariable($name)
    {
        return $this->variables[$name] = new Variable($this);
    }

    public function whenCreating($type)
    {
        if (false === isset($this->contexts[$type])) {
            $this->contexts[$type] = new Context($this);
        }

        return $this->contexts[$type];
    }

    public function forType($type)
    {
        if (false === isset($this->types[$type])) {
            $this->types[$type] = new Type();
        }

        return $this->types[$type];
    }

    public function wrapWith($type)
    {
        array_push($this->wrappers, $type);
    }

    public function create($type, $nesting = array())
    {
        $lifecycle = $this->pickFactory($type, $this->repository()->candidatesFor($type));
        $context = $this->determineContext($lifecycle->class);
        $wrapper = $context->hasWrapper($type, $nesting);
        if ($wrapper) {
            return $this->create($wrapper, $this->cons($wrapper, $nesting));
        }
        $instance = $lifecycle->instantiate($context->createDependencies(
                        $this->repository()->getConstructorParameters($lifecycle->class), $this->cons($lifecycle->class, $nesting)));
        $this->invokeSetters($context, $nesting, $lifecycle->class, $instance);

        return $instance;
    }

    public function pickFactory($type, $candidates)
    {
        if (count($candidates) == 0) {
            throw new CannotFindImplementation($type);
        }

        $preference = $this->preferFrom($candidates);
        if ($preference) {
            return $preference;
        }

        if (count($candidates) == 1) {
            return new Factory($candidates[0]);
        }

        return $this->parent->pickFactory($type, $candidates);
    }

    public function hasWrapper($type, $already_applied)
    {
        $wrappers = $this->wrappersFor($type);
        foreach ($wrappers as $wrapper) {
            if (false === in_array($wrapper, $already_applied)) {
                return $wrapper;
            }
        }

        return false;
    }

    private function invokeSetters($context, $nesting, $class, $instance)
    {
        foreach ($context->settersFor($class) as $setter) {
            $context->invoke($instance, $setter, $context->createDependencies(
                            $this->repository()->getParameters($class, $setter), $this->cons($class, $nesting)));
        }
    }

    private function settersFor($class)
    {
        $setters = isset($this->types[$class]) ? $this->types[$class]->setters : array();

        return array_values(array_keys(array_flip(array_merge(
                                                $setters, $this->parent->settersFor($class)))));
    }

    public function wrappersFor($type)
    {
        return array_values(array_merge(
                                $this->wrappers, $this->parent->wrappersFor($type)));
    }

    public function createDependencies($parameters, $nesting)
    {
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

    private function instantiateParameter($parameter, $nesting)
    {
        $hint = $parameter->getClass();
        if ($hint) {
            return $this->create($hint->getName(), $nesting);
        }

        if (true === isset($this->variables[$parameter->getName()])) {
            if ($this->variables[$parameter->getName()]->preference instanceof Lifecycle) {
                return $this->variables[$parameter->getName()]->preference->instantiate(array());
            }

            if (false === is_string($this->variables[$parameter->getName()]->preference)) {
                return $this->variables[$parameter->getName()]->preference;
            }

            return $this->create($this->variables[$parameter->getName()]->preference, $nesting);
        }

        return $this->parent->instantiateParameter($parameter, $nesting);
    }

    private function determineContext($class)
    {
        foreach ($this->contexts as $type => $context) {
            if (true === $this->repository()->isSupertype($class, $type)) {
                return $context;
            }
        }

        return $this;
    }

    private function invoke($instance, $method, $arguments)
    {
        call_user_func_array(array($instance, $method), $arguments);
    }

    private function preferFrom($candidates)
    {
        foreach ($this->registry as $preference) {
            if (true === $preference->isOneOf($candidates)) {
                return $preference;
            }
        }

        return false;
    }

    private function cons($head, $tail)
    {
        array_unshift($tail, $head);

        return $tail;
    }

    public function repository()
    {
        return $this->parent->repository();
    }

}

class Variable
{
    public $preference;
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function willUse($preference)
    {
        $this->preference = $preference;

        return $this->context;
    }

    public function useString($string)
    {
        $this->preference = new Value($string);

        return $this->context;
    }

}

class Type
{
    public $setters = array();

    public function call($method)
    {
        array_unshift($this->setters, $method);
    }

}
