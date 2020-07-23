<?php


namespace App\Storage;


class SessionStorage
{
    public function  __construct()
    {
        session_start();
    }

    public function get($key)
    {
        return $_SESSION[$key];
    }

    public function set($key, $value)
    {
        if(is_object($value)){
            $_SESSION[$key] = serialize($value);
            return;
        }

        $_SESSION[$key] = $value;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }
    public function dump()
    {
        var_dump($_SESSION);
    }
}
