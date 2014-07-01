<?php

namespace App\AppBundle\Entity;

class BaseEntity
{
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func([$this, $name]);
        }

        return $this->$name;
    }

    public function __get($name)
    {
        if (method_exists($this, 'get' . ucfirst($name))) {
            return call_user_func([$this, 'get' . ucfirst($name)]);
        }

        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (method_exists($this, 'set' . ucfirst($name))) {
            call_user_func([$this, 'set' . ucfirst($name)], $value);
        } else {
            $this->$name = $value;
        }

        return $this;
    }
}