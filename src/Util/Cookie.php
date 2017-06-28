<?php

namespace Newspaper\Util;

class Cookie {

    private $cookie_name;
    private $values;

    public function addCookie($nome, $valores) {
        $this->cookie_name = $nome;
        $this->cookie_value = $valores;
        setcookie($this->cookie_name, $this->cookie_value, time() + (86400 * 30), "/");
    }

    public function getCookie($name) {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return false;
        }
    }

    public function remover($name, $valores) {
        if (isset($_COOKIE[$name])) {
            $this->cookie_name = $name;
            $this->cookie_value = $valores;
            setcookie($this->cookie_name, $this->cookie_value, time() + (86400 * -30), "/");
        } else {
            return false;
        }
    }

    function __construct() {
        
    }

}
