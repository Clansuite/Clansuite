<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Code;

/**
 * Koch Framework - Class for Reflection and Introspection of Classes.
 *
 * Purpose of this class is to reverse-engineer classes,
 * interfaces, functions, methods and extensions.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Reflection
 */
class Reflection
{
    private $classname = '';

    /**
     * constructor
     *
     * @param string $classname
     */
    public function __construct( $classname = null)
    {
        $this->setClassName($classname);
    }

    /**
     * Set the name of the class to reflect
     *
     * @param string $classname
     */
    protected function setClassName($classname)
    {
        $this->classname = $classname;
    }

    /**
     * Get the name of the class to reflect
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->classname;
    }

    /**
     * Returns all methods of a class, excluding the ones specified in param.
     *
     * @param $exclude_classnames
     * @return array Methods of the class.
     */
    public function getMethods($exclude_classnames = null)
    {
        $methods_array = array();

        // if exlcude_classnames is a string, turn into array
        $exclude_classnames = (array) $exclude_classnames;

        // check if the class to reflect is available
        if (class_exists($this->getClassName())) {
            $class = new ReflectionClass($this->getClassName());
        } else {
            echo 'Class not existing.';
        }

        // get all methods of that class
        $methods = $class->getMethods();

        foreach ($methods as $method) {
            // get the declaring classname, might be the parent class
            $className = $method->getDeclaringClass()->getName();

            // if the classname is not excluded
            if (false === in_array($className, $exclude_classnames)) {
                // add the method name to the array
                $methods_array[$className][] = $method->getName();

                // get parameter names
                #foreach($method->getParameters() as $parameter)
                #{
                //    $parameterName = $parameter->getName();
                #}
            }
        }

        return $methods_array;
    }
}
