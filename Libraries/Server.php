<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 020 20.07.18
 * Time: 12:27
 */

namespace API\Libraries;


class Server
{
    public $HttpHost;
    public $ServerIP;
    public $ServerPort;
    public $RequestScheme;
    public $RequestMethod;
    public $HttpUserAgent;



    public function __construct()
    {
        $this->HttpHost = $_SERVER["HTTP_HOST"];
        $this->RequestScheme = $_SERVER["REQUEST_SCHEME"];
        $this->ServerIP = $_SERVER["SERVER_ADDR"];
        $this->ServerPort = $_SERVER["SERVER_PORT"];
        $this->RequestMethod = $_SERVER["REQUEST_METHOD"];
        $this->HttpUserAgent = new HttpUserAgent($_SERVER["HTTP_USER_AGENT"]);

//        $KeysVars = array_keys(get_class_vars(__CLASS__));
//
//        foreach ($_SERVER as $field => $value) {
////            $string = new Str(strtolower($field));
////            $field = $string->
////            ExplodeToWords()->
////            ImplodeWords()->
////            String;
////            if (!in_array($field, $KeysVars)) {
////                $field = "NEW_" . $field;
////                $this->$field = $value;
////            }
//            $this->$field = $value;
//
//        }

    }

}
