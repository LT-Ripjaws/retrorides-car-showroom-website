<?php
namespace App\Core;

use ReflectionClass;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, callable $factory, bool $singleton = false): void
    {
        $this->bindings[$abstract] = ['factory' => $factory, 'singleton' => $singleton];
    }

    public function make(string $class)
    {
        // Check instances first
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            $factory = $this->bindings[$class]['factory'];
            $object = $factory($this);

            if ($this->bindings[$class]['singleton']) {
                $this->instances[$class] = $object;
            }
            return $object;
        }

        // Auto-resolve
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            $object = new $class;
        } else {
            $params = [];
            foreach ($constructor->getParameters() as $param) {
                $type = $param->getType();

                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $params[] = $this->make($type->getName());
                } elseif ($param->isDefaultValueAvailable()) {
                    $params[] = $param->getDefaultValue();
                } else {
                    throw new \Exception(
                        "Cannot resolve parameter \${$param->getName()} in $class"
                    );
                }
            }
            $object = $reflection->newInstanceArgs($params);
        }
        
        // Cache auto-resolved instances as singletons 
        $this->instances[$class] = $object;
        return $object;
    }
}
