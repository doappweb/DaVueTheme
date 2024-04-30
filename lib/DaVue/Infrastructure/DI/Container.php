<?php

namespace SuiteCRM\DaVue\Infrastructure\DI;

use ReflectionClass;
use ReflectionException;

/**
 * A simple implementation of the DI Container
 */
class Container
{
    protected static $instance;

    private $config;
    private $services = [];

    private function __construct() {

        $coreConf = require 'configMap.php';

        if (file_exists('custom/lib/DaVue/Infrastructure/classMap.php')){
            $customConf = include 'custom/lib/DaVue/Infrastructure/classMap.php';
        } else {
            $customConf = [];
        }

        $this->config = array_merge($coreConf, $customConf);
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function getConf($key): array
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return [];
        }
    }

    private function getAlias($id)
    {
        if (false === strpos($id, '@')) {
            return $id;
        }

        if (isset($this->config['services'][$id])){
            return $this->config['services'][$id];
        }

        return $id;
    }

    /**
     * @throws ReflectionException
     */
    public function get($class, $arguments = []): object
    {
        $class = $this->getAlias($class);

        if (!$this->has($class)) {
            $this->services[$class] = $this->prepareObject($class, $arguments);
        }

        return $this->services[$class];
    }

    /**
     * @throws ReflectionException
     */
    public function getNew($class, $arguments = []): object
    {
        $this->services[$class] = $this->prepareObject($class, $arguments);

        return $this->services[$class];
    }

    public function set(string $id, object $service): self
    {
        $this->services[$id] = $service;

        return $this;
    }

    private function prepareArguments(array $arguments)
    {
        foreach ($arguments as $argument) {

            if (gettype($argument) === 'object') {
                $paramId = get_class($argument);
            } else {
                $paramId = null;
            }

            if ($paramId) {

                if ($interface = class_implements($argument)) {
                    $intersection = array_intersect_key($interface, $this->services);

                    if (!empty($intersection)) {
                        $paramId = current($intersection);
                    }
                }

                $this->set($paramId, $argument);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function prepareObject(string $class, $arguments): object
    {
        if ($class === self::class) {
            return $this;
        }

        if (!empty($arguments)) {
            $this->prepareArguments($arguments);
        }

        $classReflector = new ReflectionClass($class);

        // If the desired class = interface, we are looking for the declared implementation among the services
        if ($classReflector->isInterface()) {
            //$implements = [];

            foreach ($this->services as $service) {
                if ($service instanceof $class) {
                    //$implements[] = $service;
                    return $service;
                }
            }

            //return $implements[0];
        }

        // If there is no constructor, we immediately return an instance of the class
        $constructReflector = $classReflector->getConstructor();
        if (empty($constructReflector)) {
            return new $class;
        }

        // If there are no arguments for the constructor, we immediately return an instance of the class
        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }

        // We go through all the arguments of the constructor, collect their values
        $args = [];
        $count = 0;
        foreach ($constructArguments as $parameter) {
            // Getting the type of the argument
            if ($parameter->getType() && !$parameter->getType()->isBuiltin()) {
                $parameterType = $parameter->getType()->getName();
                // We get the argument itself by its type from the container
                $args[$parameter->getName()] = $this->get($parameterType);
            } else {
                if (isset($arguments[$count])) {
                    $args[$parameter->getName()] = $arguments[$count];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $args[$parameter->getName()] = $parameter->getDefaultValue();
                } else {
                    $args[$parameter->getName()] = null;
                }
            }
            $count++;
        }

        $params = array_values($args);

        // return an instance of the class with all dependencies
        return new $class(...$params);
    }
}
