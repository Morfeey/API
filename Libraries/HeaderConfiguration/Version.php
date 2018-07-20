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
    public $Version;

    public function __construct()
    {
        $this->Headers = new Headers();
        $this->Version = $this->getCurrent();
    }
    /* Directory Version */
    private function getDirectoryVersions (): string {
        $Directory = new Directory(__DIR__);
        $result = $Directory
            ->upLevel()
            ->upLevel()
            ->merge($this->Headers->APIName)
            ->merge("Versions")->Directory;
        return $result;
    }

    /**
     * @return array Objects VersionInfo
     * @throws \Exception
     */
    private function getListVersions (): array {
        $Directory = new Directory($this->getDirectoryVersions());
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
            include_once $File->File;
            $ObjectName = $File->getNameSpaceAndClassOnName();
            $ObjectInfo = new $ObjectName();
            array_push($result, $ObjectInfo);
        }

        uasort($result, function ($p1, $p2) {
            if ($p1->Version < $p2->Version) {
                $result = -1;
            } else if ($p1->Version > $p2->Version) {
                $result = 1;
            } else $result = 0;
            return $result;
        });

        return $result;
    }

    public function getLastVersion (): float {
        $result = 0;
        $versions = $this->getListVersions();
        foreach ($versions as $version) {
            $result = ($version->Version> $result) ? $version->Version : $result;
        }
        return $result;
    }

    public function getDirectoryCurrentVersion() {
        $Version = $this->Version;
        $Versions = $this->getListVersions();
        foreach ($Versions as $version) {
            if ($version->Version == $Version){
                $class = get_class($version);
                $explode = explode("\\", $class);
                $LastElement = count($explode)-1;
                unset($explode[$LastElement]);
                $Directory = new Directory(Directory::getDocumentRoot(), implode("/", $explode));
                $result = $Directory->Directory;
                break;
            }
        }
        return $result;
    }

    public function getCurrent () {
        $result = 0;
        $HeaderVersion = $this->Headers->Version;
        $VersionCorrect = function ()use ($HeaderVersion) {
            $Versions = $this->getListVersions();
            $result = $Versions;
            foreach ($Versions as $version) {
                $Version = $version->Version;
                $result["$Version"] = (float) $Version - (float) $HeaderVersion;
            }
            asort($result);
            $result = (float) array_keys($result)[0];
            return $result;
        };
        $result = (is_string($HeaderVersion) && strtolower($HeaderVersion) === "last") ? $this->getLastVersion() : $VersionCorrect();

        return $result;
    }
}