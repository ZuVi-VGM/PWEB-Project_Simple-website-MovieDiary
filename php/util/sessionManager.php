<?php

class sessionManager
{
    function __construct()
    {
        session_start();
        session_regenerate_id();
    }

    public function login($username, $isadmin = 0)
    {
        if(!$this->validate()) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $username;
            $_SESSION['is_admin'] = $isadmin;
        }
    }

    public function validate()
    {
        return isset($_SESSION['logged_in']);
    }

    public function close()
    {
        if($this->validate()) {
            unset($_SESSION['logged_in']);
            unset($_SESSION['user']);
        }
    }
}