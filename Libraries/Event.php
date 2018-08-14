<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 014 14.08.18
 * Time: 10:31
 */

namespace API\Libraries;


class Event
{
    static private $callbacks = array();

    static function addAction(string $action, \Closure $fun){
        self::$callbacks[] = [
            $action => $fun
        ];
    }

    static function doAction(string $name, array $arg = null) {
        foreach (self::$callbacks as $callback) {
            if (array_key_exists($name, $callback)) {
                if (!is_callable ($callback[$name]) ) {
                    throw new Exception ( "Функция обратного вызова - невызываемая ! " ) ;
                }
                call_user_func_array($callback[$name], $arg);
            }
        }
    }

}