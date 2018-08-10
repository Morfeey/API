<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.06.2018
 * Time: 10:01
 */

namespace API;

include_once "config.php";
include_once "Libraries/Path/Directory.php";
include_once "Libraries/Path/Subject/SearchOption.php";
include_once "Libraries/Headers.php";
include_once "Libraries/HeaderConfiguration/Versions.php";
include_once "Libraries/HeaderConfiguration/Head.php";
include_once "Libraries/Subject/Header.php";
include_once "Libraries/Str.php";

use API\Libraries\HeaderConfiguration\Version;
use API\Libraries\HeaderConfiguration\Versions;
use API\Libraries\Headers;
use API\Libraries\Path\Directory;
use API\Libraries\Path\Subject\SearchOption;
use API\Libraries\Str;

class Autoloader
{
    private $ListLoader;
    private $Headers;
    private $Versions;

    private $PatternFiles = "*.php";


    /**
     * @return array
     * @throws \Exception
     */
    private function getFiles(): array
    {

        $PatternFiles = "*.php";
        $result = [];
        unset($this
                ->ListLoader
            [$this
                ->Versions
                ->getDirectoryVersions()
            ]);
        foreach ($this->ListLoader as $LoadItem) {
            $directoryItem = new Directory($LoadItem);
            $files = $directoryItem->getFiles($PatternFiles, SearchOption::Recurse());
            $result = array_merge($result, $files);
        }
        return $result;
    }

    public function includeFiles (array $ListFiles) {
        foreach ($ListFiles as $file) {
            if (file_exists($file)) {
                include_once $file;
            }
        }
        return $this;
    }

    /**
     * Autoloader constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->Headers = new Headers();
        $this->Versions = new Versions();

        $this->ListLoader = array_merge([
            __DIR__."/Libraries",
            __DIR__. "/". $this->Headers->APIName,
            $this
                ->Versions
                ->getCurrent()
                ->getDirectory()

        ], \APIDefault::ProjectFilesOnAutoloader);
        $files = $this->getFiles();
        foreach ($files as $file) {
            print "$file<br>";
            include_once $file;
        }

    }

}


spl_autoload_register(function () {
    new Autoloader();
});