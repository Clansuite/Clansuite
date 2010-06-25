<?php
abstract class Lifecycle {
    public $class;

    function __construct($class) {
        $this->class = $class;
        $this->triggerAutoload($class);
    }
    
    private function triggerAutoload($class) {
        class_exists($class);
    }

    function isOneOf($candidates) {
        return in_array($this->class, $candidates);
    }

    abstract function instantiate($dependencies);
}

class Value extends Lifecycle {
    private $instance;

    function __construct($instance) {
        $this->instance = $instance;
    }

    function instantiate($dependencies) {
        return $this->instance;
    }
}

class Factory extends Lifecycle {
    function instantiate($dependencies) {
        return call_user_func_array(
                array(new ReflectionClass($this->class), 'newInstance'),
                $dependencies);
    }
}

class Reused extends Lifecycle {
    private $instance;

    function instantiate($dependencies) {
        if (! isset($this->instance)) {
            $this->instance = call_user_func_array(
                    array(new ReflectionClass($this->class), 'newInstance'),
                    $dependencies);
        }
        return $this->instance;
    }
}

class Sessionable extends Lifecycle {
    private $slot;

    function __construct($class, $slot = false) {
        parent::__construct($class);
        $this->slot = $slot ? $slot : $class;
    }

    function instantiate($dependencies) {
        @session_start();
        if (! isset($_SESSION[$this->slot])) {
            $_SESSION[$this->slot] = call_user_func_array(
                    array(new ReflectionClass($this->class), 'newInstance'),
                    $dependencies);
        }
        return $_SESSION[$this->slot];
    }
}
?>