<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 14:00
 */

namespace API\REST\Configuration\HeadersCorrections;


use API\REST\Headers;

class Readiness
{
    private $Headers;

    public function Correct () {
        $Readiness = $this->Headers->Readiness;
        $Readiness = (is_string($Readiness)) ? strtolower($Readiness) : $Readiness;
        $result = ($Readiness === "debug") ? false : (is_bool($Readiness)) ? $Readiness : true;
        return $result;
    }

    public function __construct(Headers $Item)
    {
        $this->Headers = $Item;
    }

}