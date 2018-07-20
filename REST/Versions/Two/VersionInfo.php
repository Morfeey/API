<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 017 17.07.18
 * Time: 12:03
 */

namespace API\REST\Versions\Two;


use API\Libraries\HeaderConfiguration\VersionInfoTemplate;

class VersionInfo extends VersionInfoTemplate
{
    public $Version = 0.7;
    public $Readiness = false;

}