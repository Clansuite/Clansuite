<?php
class ClassRepository {
    private static $reflection = false;

    function __construct() {
        if (! self::$reflection) {
            self::$reflection = new ReflectionCache();
        }
        self::$reflection->refresh();
    }

    function candidatesFor($interface) {
        return array_merge(
                self::$reflection->concreteSubgraphOf($interface),
                self::$reflection->implementationsOf($interface));
    }

    function isSupertype($class, $type) {
        $supertypes = array_merge(
                array($class),
                self::$reflection->interfacesOf($class),
                self::$reflection->parentsOf($class));
        return in_array($type, $supertypes);
    }

    function getConstructorParameters($class) {
        $reflection = self::$reflection->reflection($class);
        if ($constructor = $reflection->getConstructor()) {
            return $constructor->getParameters();
        }
        return array();
    }

    function getParameters($class, $method) {
        $reflection = self::$reflection->reflection($class);
        if (! $reflection->hasMethod($method)) {
            throw new SetterDoesNotExist();
        }
        return $reflection->getMethod($method)->getParameters();
    }
}

class ReflectionCache {
    private $implementations_of = array();
    private $interfaces_of = array();
    private $reflections = array();
    private $subclasses = array();
    private $parents = array();

    function refresh() {
        $this->buildIndex(array_diff(get_declared_classes(), $this->indexed()));
        $this->subclasses = array();
    }

    function implementationsOf($interface) {
        return isset($this->implementations_of[$interface]) ?
                $this->implementations_of[$interface] : array();
    }

    function interfacesOf($class) {
        return isset($this->interfaces_of[$class]) ?
                $this->interfaces_of[$class] : array();
    }

    function concreteSubgraphOf($class) {
        if (! class_exists($class)) {
            return array();
        }
        if (! isset($this->subclasses[$class])) {
            $this->subclasses[$class] = $this->isConcrete($class) ? array($class) : array();
            foreach ($this->indexed() as $candidate) {
                if (is_subclass_of($candidate, $class) && $this->isConcrete($candidate)) {
                    $this->subclasses[$class][] = $candidate;
                }
            }
        }
        return $this->subclasses[$class];
    }

    function parentsOf($class) {
        if (! isset($this->parents[$class])) {
            $this->parents[$class] = class_parents($class);
        }
        return $this->parents[$class];
    }

    function reflection($class) {
        if (! isset($this->reflections[$class])) {
            $this->reflections[$class] = new ReflectionClass($class);
        }
        return $this->reflections[$class];
    }

    private function isConcrete($class) {
        return ! $this->reflection($class)->isAbstract();
    }

    private function indexed() {
        return array_keys($this->interfaces_of);
    }

    private function buildIndex($classes) {
        foreach ($classes as $class) {
            $interfaces = array_values(class_implements($class));
            $this->interfaces_of[$class] = $interfaces;
            foreach ($interfaces as $interface) {
                $this->crossReference($interface, $class);
            }
        }
    }

    private function crossReference($interface, $class) {
        if (! isset($this->implementations_of[$interface])) {
            $this->implementations_of[$interface] = array();
        }
        $this->implementations_of[$interface][] = $class;
        $this->implementations_of[$interface] =
                array_values(array_unique($this->implementations_of[$interface]));
    }
}
?>