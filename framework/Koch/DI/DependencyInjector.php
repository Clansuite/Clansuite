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

use Koch\DI\Engine\Context;
use Koch\DI\Storage\ClassRepository;
use Koch\DI\Exception\CannotDetermineImplementation;
use Koch\DI\Exception\MissingDependency;

/**
 * Phemto - A Dependency Injector by Markus Baker.
 *
 * Version: 0.1_alpha10 - SVN-Revision: 90
 *
 * @author Markus Baker
 * @license Public Domain
 * @link http://phemto.sourceforge.net/index.php
 *
 */
class DependencyInjector
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
