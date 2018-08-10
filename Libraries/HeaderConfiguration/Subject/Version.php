<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 010 10.08.18
 * Time: 11:42
 */

namespace API\Libraries\HeaderConfiguration\Subject;

include_once __DIR__."/../../Path/File.php";
include_once __DIR__."/../../Path/Directory.php";
include_once __DIR__."/../../Str.php";
include_once __DIR__."/../Head.php";

use API\Libraries\HeaderConfiguration\Head;
use API\Libraries\Path\Directory;
use API\Libraries\Path\File;
use API\Libraries\Str;

class Version extends Head
{
    public $Version;
    public $Readiness;
    protected $NameFileVersionInfo = "VersionInfo.json";
    protected $Directory;
    private $isFileExists = true;

    public function isActive () {
        return $this->isFileExists;
    }
    public function getDirectory (): string {
        return $this->Directory->Directory;
    }

    public function getFile (): string {
        return $this->Directory->getLastSlash()->Directory.$this->NameFileVersionInfo;
    }

    public function __construct(Directory $directoryVersion)
    {
        $this->Directory = $directoryVersion;
        $file = $this->getFile();
        $file = new File($file);
        if ($file->isExist()) {
            $jsonVersion = $file->getContent();
            foreach (json_decode($jsonVersion, true) as $key=>$value) {
                $stringKey = new Str($key);
                $key = $stringKey
                    ->toLow()
                    ->firstCharUp()->
                    String;
                $this->$key = $value;
            }
        }else {
            $this->isFileExists = false;
        }
    }
}