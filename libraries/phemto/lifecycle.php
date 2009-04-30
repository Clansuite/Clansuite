<?php
abstract class Lifecycle {
    public $class;

    function __construct($class) {
        $this->class = $class;
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
        $this->class = get_class($this->instance);
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

    function __construct($slot, $class) {
        $this->slot = $slot;
        parent::__construct($class);
    }

    function instantiate($dependencies) {
        if (! isset($_SESSION[$this->slot])) {
            $_SESSION[$this->slot] = call_user_func_array(
                    array(new ReflectionClass($this->class), 'newInstance'),
                    $dependencies);
        }
        return $_SESSION[$this->slot];
    }
}
?>