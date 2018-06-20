<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.06.2018
 * Time: 15:29
 */

namespace API\REST;
use API\REST\CONFIGURATION\HeaderValues;


class Config
{
    public $DefaultHeaderValues;
    public function __construct()
    {
        $this->DefaultHeaderValues = new HeaderValues();
    }
}