<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 006 06.08.18
 * Time: 16:42
 */

namespace API\Libraries\HeaderConfiguration;

use API\Libraries\Str;

class Head
{
    public function valid ($keyHeader): string {
        $str = new Str($keyHeader);
        $result = $str
            ->replace(["_"], "-")
            ->toLow()
            ->String;

        return $result;
    }
    public function validList (array $ListHeaders) {
        $result = [];
        foreach ($ListHeaders as $value) {
            $result[] = $this->valid($value);
        }
        return $result;
    }
    public function validListKeys(array $ListHeaders):array {
        $result= [];
        foreach ($ListHeaders as $key=>$value) {
            $result[$this->valid($key)] = $value;
        }
        return $result;
    }
}