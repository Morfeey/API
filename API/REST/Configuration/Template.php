<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.06.2018
 * Time: 10:41
 */

namespace API\REST\Configuration;

use API\REST\Main;

abstract class Template extends Main
{
    const Version = 0;
    const Readiness = false;

    abstract function Result();
}