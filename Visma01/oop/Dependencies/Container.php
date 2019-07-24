<?php

namespace Dependencies;

use Dependencies\Interfaces\ContainerInterface;
use ReflectionClass;
use Exception;
use Closure;

class Container implements ContainerInterface
{

    protected $instances = [];

    public function set($abstract, $concrete = NULL)
    {
        if ($concrete === NULL) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }

    public function has($id){}

    public function get($abstract, $parameters = [])
    {
        // if we don't have it, just register it
        if (!isset($this->instances[$abstract])) {
            $this->set($abstract);
        }
        return $this->resolve($this->instances[$abstract], $parameters);
    }

    public function resolve($concrete, $parameters)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }
        $reflector = new ReflectionClass($concrete);
        // check if class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        // get class constructor
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            // get new instance from class
            return $reflector->newInstance();
        }
        // get constructor params
        $parameters   = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        // get new instance with dependencies resolved
        return $reflector->newInstanceArgs($dependencies);
    }

    public function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            // get the type hinted class
            $dependency = $parameter->getClass();
            if ($dependency === NULL) {
                // check if default value for a parameter is available
                if ($parameter->isDefaultValueAvailable()) {
                    // get default value of parameter
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Can not resolve class dependency {$parameter->name}");
                }
            } else {
                // get dependency resolved
                $dependencies[] = $this->get($dependency->name);
            }
        }
        return $dependencies;
    }
}