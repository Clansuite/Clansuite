<?php

abstract class Lifecycle
{
    public $class;

    public function __construct($class)
    {
        $this->class = $class;

        # triggerAutoload
        class_exists($class, true);
    }

    public function isOneOf($candidates)
    {
        return in_array($this->class, $candidates);
    }

    abstract public function instantiate($dependencies);
}

class Value extends Lifecycle
{
    private $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function instantiate($dependencies)
    {
        return $this->instance;
    }

}

class Factory extends Lifecycle
{

    public function instantiate($dependencies)
    {
        return call_user_func_array(
                        array(new ReflectionClass($this->class), 'newInstance'), $dependencies);
    }

}

class Reused extends Lifecycle
{
    private $instance;

    public function instantiate($dependencies)
    {
        if (false === isset($this->instance)) {
            $this->instance = call_user_func_array(
                    array(new ReflectionClass($this->class), 'newInstance'), $dependencies);
        }

        return $this->instance;
    }

}

class Sessionable extends Lifecycle
{
    private $slot;

    public function __construct($class, $slot = false)
    {
        parent::__construct($class);
        $this->slot = $slot ? $slot : $class;
    }

    public function instantiate($dependencies)
    {
        @session_start();
        if (false === isset($_SESSION[$this->slot])) {
            $_SESSION[$this->slot] = call_user_func_array(
                    array(new ReflectionClass($this->class), 'newInstance'), $dependencies);
        }

        return $_SESSION[$this->slot];
    }

}
