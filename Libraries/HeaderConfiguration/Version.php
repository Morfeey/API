<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 14:59
 */

namespace API\Libraries\HeaderConfiguration;


use API\Libraries\Headers;
use API\Libraries\Path\Directory;
use API\Libraries\Path\File;
use API\Libraries\Path\Subject\SearchOption;

class Version
{

    private $Headers;

    public function __construct()
    {
        $this->Headers = new Headers();
    }

    public function getDirectory (): string {
        $Directory = new Directory(__DIR__);
        $result = $Directory
            ->upLevel()
            ->upLevel()
            ->merge($this->Headers->APIName)
            ->merge("Versions")->Directory;
        return $result;
    }

    public function getListVersions () {
        $Directory = new Directory($this->getDirectory());
        $VersionDirectories = $Directory->GetDirectories("*", SearchOption::Current());
        $VersionInfoFiles = [];

        foreach ($VersionDirectories as $directory) {
            $Directory = new Directory($directory);
            $VersionInfo = $Directory->GetFiles("VersionInfo.php", SearchOption::Current());
            $VersionInfoFiles = array_merge($VersionInfoFiles, $VersionInfo);
        }

        $result = [];
        foreach ($VersionInfoFiles as $file) {
            $File = new File($file);
            //include_once $File->File;
            $ObjectName = $File->getNameSpaceAndClassOnName();
            $ObjectInfo = new $ObjectName();
            array_push($result, $ObjectInfo);
        }


        return $result;
    }

    public function correct () {

    }
}