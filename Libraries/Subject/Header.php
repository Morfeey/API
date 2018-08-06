<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 006 06.08.18
 * Time: 15:36
 */

namespace API\Libraries\Subject;

include_once __DIR__."/../HeaderConfiguration/Head.php";

use API\Libraries\HeaderConfiguration;


class Header extends HeaderConfiguration\Head
{
    private $key;
    private $value;

    public function setKey ($key) {
        $key = $this->valid($key);
        $this->key = $key;
        return $this;
    }
    public function getKey () {
        return $this->key;
    }

    public function setValue ($value) {
        $this->value = $value;
        return $this;
    }
    public function getValue () {
        return $this->value;
    }

    public function __construct()
    {

    }
}