<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container{
    private array $definitions = [];
    private array $resolved = [];

    public function addDefinitions(array $newDefinition)
    {
        $this->definitions = [...$this->definitions, ...$newDefinition];

    }

    public function resolve(string $classname){
        $reflectionClass = new ReflectionClass($classname);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$classname} is not instantiable");
        }
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $classname;
        }

        $params = $constructor->getParameters();

        if (count($params) === 0){
            return new $classname;
        }

        $dependencies = [];

        foreach ($params as $param){
            $name = $param->getName();
            $type = $param->getType();

            if(!$type){
                throw new ContainerException("Failed to resolve class {$classname} because param {$name} is missing a type hint");
            }

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException("Failed to resolve class {$classname} because invalid param name");
            }
            
            $dependencies[] = $this->get($type->getName());
        }

        


        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerException("Class {$id} does not exist in container");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factory = $this->definitions[$id];
        $dependency = $factory($this);

        $this->resolved[$id] = $dependency;

        return $dependency;

    }
}
