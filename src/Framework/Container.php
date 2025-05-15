<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass;

class Container{
    private array $definitions = [];

    public function addDefinitions(array $newDefinition)
    {
        $this->definitions = [...$this->definitions, ...$newDefinition];

    }

    public function resolve(string $classname){
        $reflectionClass = new ReflectionClass($classname);

        dd($reflectionClass);
    }

}
