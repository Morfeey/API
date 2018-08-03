<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 025 25.07.18
 * Time: 14:35
 */

namespace API\Libraries;


class HttpUserAgent
{
    private $UserAgent;
    private $OSInfo;

    public $Browser;
    public $BrowserVersion;
    public $OperatingSystem;
    public $VersionOperationSystem;
    public $TheBitnessOfTheOperatingSystem;


    public function __construct($UserAgent = null)
    {
        $this->UserAgent = (is_null($UserAgent)) ? $_SERVER["HTTP_USER_AGENT"] : $UserAgent;

        $explode = preg_split("//", $this->UserAgent);
        $OSInfo = $explode[1];
        $BrowserInfo = $explode[2];
        $this->OSInfo = explode(";", $OSInfo);

    }


}
