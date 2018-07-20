<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 020 20.07.18
 * Time: 12:27
 */

namespace API\Libraries;


class UserAgent
{
    public $HttpHost;
    public $Protocol;
    public $ServerIP;
    public $ServerPort;
    public $RequestScheme;
    public $RequestMethod;


    public function __construct()
    {
        $this->HttpHost = $_SERVER["HTTP_HOST"];
        $this->RequestScheme = $_SERVER["REQUEST_SCHEME"];
        $this->ServerIP = $_SERVER["SERVER_ADDR"];
        $this->ServerPort = $_SERVER["SERVER_PORT"];
        $this->RequestMethod = $_SERVER["REQUEST_METHOD"];

        foreach ($_SERVER as $field => $value) {
            $string = new Str(strtolower($field));
            $field = $string->
            ExplodeToWords()->
            SpaceOnWords()->
            String;
            $this->$field = $value;
        }

    }

}
