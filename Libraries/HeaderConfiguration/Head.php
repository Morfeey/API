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
}