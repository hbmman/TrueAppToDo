<?php
declare(strict_types=1);


namespace Engine\Container;


class Container
{
    private array $definitions = [];

    public function set($key, $definition)
    {
        $this->definitions[$key] = $definition;
    }

    public function get($key)
    {
        if(!isset($this->definitions[$key])){
            throw new \LogicException("Service not found " . $key);
        }

        if(is_callable($this->definitions[$key])){
            $this->definitions[$key] = call_user_func($this->definitions[$key], $this);
        }
        return $this->definitions[$key];
    }
}

