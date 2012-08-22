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

namespace Koch\DI;

class ReflectionCache
{
    private $implementations_of = array();
    private $interfaces_of = array();
    private $reflections = array();
    private $subclasses = array();
    private $parents = array();

    public function refresh()
    {
        $this->buildIndex(array_diff(get_declared_classes(), $this->indexed()));
        $this->subclasses = array();
    }

    public function implementationsOf($interface)
    {
        return isset($this->implementations_of[$interface]) ?
                $this->implementations_of[$interface] : array();
    }

    public function interfacesOf($class)
    {
        return isset($this->interfaces_of[$class]) ?
                $this->interfaces_of[$class] : array();
    }

    public function concreteSubgraphOf($class)
    {
        if (false === class_exists($class)) {
            return array();
        }

        if (false === isset($this->subclasses[$class])) {
            $this->subclasses[$class] = $this->isConcrete($class) ? array($class) : array();

            foreach ($this->indexed() as $candidate) {
                if (true === is_subclass_of($candidate, $class) && $this->isConcrete($candidate)) {
                    $this->subclasses[$class][] = $candidate;
                }
            }
        }

        return $this->subclasses[$class];
    }

    public function parentsOf($class)
    {
        if (false === isset($this->parents[$class])) {
            $this->parents[$class] = class_parents($class);
        }

        return $this->parents[$class];
    }

    public function reflection($class)
    {
        if (false === isset($this->reflections[$class])) {
            $this->reflections[$class] = new \ReflectionClass($class);
        }

        return $this->reflections[$class];
    }

    private function isConcrete($class)
    {
        return !$this->reflection($class)->isAbstract();
    }

    private function indexed()
    {
        return array_keys($this->interfaces_of);
    }

    private function buildIndex($classes)
    {
        foreach ($classes as $class) {
            $interfaces = array_values(class_implements($class));
            $this->interfaces_of[$class] = $interfaces;
            foreach ($interfaces as $interface) {
                $this->crossReference($interface, $class);
            }
        }
        # show class graph
        #var_export($this->implementations_of);
    }

    private function crossReference($interface, $class)
    {
        if (false === isset($this->implementations_of[$interface])) {
            $this->implementations_of[$interface] = array();
        }
        $this->implementations_of[$interface][] = $class;
        $this->implementations_of[$interface] =
                array_values(array_keys(array_flip($this->implementations_of[$interface])));
    }

}
