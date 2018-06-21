<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.06.2018
 * Time: 8:07
 */

namespace API\Libraries\Path\Subject;


final class SearchOption
{
    public $IsRecurse;

    public static function Current () {
        return new self(false);
    }

    public static function Recurse () {
        return new self(true);
    }

    private function __construct(bool $IsRecurse) {
        $this->IsRecurse = (bool) $IsRecurse;
    }

    private function __clone() {

    }

}