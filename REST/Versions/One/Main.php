<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.06.2018
 * Time: 11:56
 */

namespace API\REST\Versions\One;


use API\REST\Configuration\Template;

class Main extends Template
{
    const Version = 1;
    const Readiness = true;

    public function __construct()
    {

    }

    public function Result()
    {
        return print_r($this->Headers, true);
    }
}