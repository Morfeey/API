<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.06.2018
 * Time: 16:32
 */

namespace API\REST\Configuration\HeadersCorrections;

use API\Libraries\Path\Directory;
use API\Libraries\Str;
use API\REST\Headers;
use API\REST\Versions;


class Version
{
    private $Headers;

    /**
     * @return array[stdClass {Version, Readiness}]   Versions API
     * @throws \Exception
     */
    public function GetVersions(): array
    {

        $Directory = new Directory(__DIR__, "../../Versions");
        $DirectoriesVersions = $Directory->GetDirectories();
        $Versions = [];

        $ToInt = function ($Ver) {
            $str = new Str((string)$Ver);
            $i = 0;
            start:
            $numStr = str_replace(" ", "", $str->IntToWord($i));
            if ($numStr !== $Ver) {
                $i++;
                goto start;
            }
            return $i;
        };
        foreach ($DirectoriesVersions as $Directory) {
            $Version = $Directory->GetLastDir();
            $IntVersion = $ToInt($Version);
            $ObjectVersion = new \stdClass();
            $ObjectVersion->Version = $IntVersion;
            $Class = "API\\REST\\Versions\\$Version\\Main";
            $ObjectVersion->Readiness = $Class::Readiness;
            array_push($Versions, $ObjectVersion);
        }


        uasort($Versions, function ($p1, $p2) {
            if ($p1->Version < $p2->Version) {
                $result = -1;
            } else if ($p1->Version > $p2->Version) {
                $result = 1;
            } else $result = 0;
            return $result;
        });
        return $Versions;
    }

    private function Legality(\stdClass $Version): bool
    {
        if (!$this->Headers->Readiness) {
            $result = true;
        }

        if ($this->Headers->Readiness && !$Version->Readiness) {
            $result = false;
        }


        if ($this->Headers->Readiness && $Version->Readiness) {
            $result = true;
        }


        return $result;
    }

    public function GetLastVersion(): float
    {
        $lastVersion = 0;
        $versions = $this->GetVersions();

        foreach ($versions as $version) {
            $lastVersion = ($version->Version > $lastVersion && $this->Legality($version)) ? $version->Version : $lastVersion;

        }
        return (float)$lastVersion;
    }

    public function Correct(): int
    {
        $Version = strtolower($this->Headers->Version);
        $VersionCorrect = function () use ($Version) {
            $Versions = $this->GetVersions();
            $Vers = [];
            foreach ($Versions as $ver) {
                array_push($Vers, $ver->Version);
            }
            if (!in_array((float)$Version, $Vers)) {
                $CurrentVersion = (float)$Version;
                $result = $this->GetLastVersion();
                foreach ($Versions as $version) {
                    if ($version->Version >= $CurrentVersion && $this->Legality($version)) {
                        $result = $version->Version;
                        break;
                    }
                }
            } else {
                $result = $Version;
            }
            return $result;
        };
        $Version = ($Version === "last") ? $this->GetLastVersion() : $VersionCorrect();
        return (int)$Version;
    }

    public function __construct(Headers $Item)
    {
        $this->Headers = $Item;
    }
}