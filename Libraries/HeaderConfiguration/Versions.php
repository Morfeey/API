<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 14:59
 */

namespace API\Libraries\HeaderConfiguration;

include_once __DIR__."/../Headers.php";
include_once __DIR__."/../Path/Directory.php";
include_once __DIR__."/../Path/Subject/SearchOption.php";
include_once __DIR__."/Subject/Version.php";

use API\Libraries\HeaderConfiguration\Subject\Version;
use API\Libraries\Headers;
use API\Libraries\Path\Directory;
use API\Libraries\Path\Subject\SearchOption;

class Versions
{

    private $Headers;
    public $Version;

    public function __construct(Headers $headers = null)
    {
        $this->Headers = (is_null($headers)) ? new Headers() : $headers;
        $this->Version = $this->getCurrent();
    }

    /** Directory Version
     * @return string
     * @throws \Exception
     */
    public function getDirectoryVersions(): string
    {
        $Directory = new Directory(__DIR__);
        $result = $Directory
            ->parent()
            ->parent()
            ->merge($this->Headers->APIName)
            ->merge("Versions")->Directory;
        return $result;
    }

    /**
     * @return array Objects Version
     * @throws \Exception
     */
    public function getList(): array
    {
        $DirectoryVersion = $this->getDirectoryVersions();
        $DirectoryVersions = new Directory($DirectoryVersion);
        $Directories = $DirectoryVersions->getDirectories("*", SearchOption::Current());
        $result= [];
        foreach ($Directories as $directory) {
            $directoryVersions = new Directory($directory);
            $Version = new Version($directoryVersions);
            if ($Version->isActive()) {
                $result[] = $Version;
            }
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

    /**
     * @return float
     * @throws \Exception //
     */
    public function getLastOnFloat(): float
    {
        return $this->getLast()->Version;
    }

    /**
     * @return Version
     * @throws \Exception
     */
    public function getLast (): Version {
        $versions = $this->getList();
        $result = $versions[0];
        foreach ($versions as $version) {
            $result = ($version->Version > $result->Version) ? $version : $result;
        }
        return $result;
    }

    /**
     * @return Version
     * @throws \Exception
     */
    public function getCurrent (): Version {
        $result = null;
        $userAssignedHeaders = $this->Headers->getUserAssigned();
        $versionUserAssigned = $userAssignedHeaders->Version;
        $versions = $this->getList();
        $versionCorrect = function () use ($versionUserAssigned, $versions) {
            $result = [];
            foreach ($versions as $version) {
                $Version = $version->Version;
                $result["$Version"] = (float)$Version - (float)$versionUserAssigned;
            }
            asort($result);

            $last_difference = null;
            $last_version = null;
            foreach ($result as $version=>$difference) {
                $difference_plus = (float)str_replace("-", "", $difference);
                $last_difference = (is_null($last_difference)) ? $difference_plus : $last_difference;
                $last_version = (is_null($last_version)) ? $version : $last_version;
                if ($difference_plus<$last_difference) {
                    $last_difference = $difference_plus;
                    $last_version = $version;
                }
            }

            $result = (float)$last_version;
            return $result;
        };
        $floatVersion = (is_string($versionUserAssigned) && strtolower($versionUserAssigned) === "last") ? $this->getLastOnFloat() : $versionCorrect();

        foreach ($versions as $version) {
            if ($version->Version = $floatVersion) {
                $result = $version;
                break;
            }
        }

        return $result;
    }

    /**
     * @return array|float|int
     */
    public function getCurrentOnFloat(): float
    {
        return (float) $this->getCurrent()->Version;
    }
}