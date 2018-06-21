<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 16:52
 */

namespace API\REST\Versions\One;


use API\Libraries\HeaderConfiguration\VersionInfoTemplate;

class VersionInfo extends VersionInfoTemplate
{
    public $Version = 1;
    public $Readiness = true;


    public function __construct()
    {
        
    }
}