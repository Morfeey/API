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
include_once "Libraries/HeaderConfiguration/Head.php";
include_once "Libraries/Subject/Header.php";
include_once "Libraries/Str.php";

use API\Libraries\Headers;
use API\Libraries\Path\Directory;
use API\Libraries\Path\Subject\SearchOption;

class Autoloader
{
    private $ListLoader;

    /**
     * @return array
     * @throws \Exception
     */
    private function getFiles(): array
    {
        $result = [];
        foreach ($this->ListLoader as $LoadItem) {
            $Directory = new Directory(__DIR__, $LoadItem);
            $files = $Directory->getFiles("*.php", SearchOption::Recurse());
            $result = array_merge($result, $files);
        }
        return $result;
    }

    /**
     * Autoloader constructor.
     * @param array|null $ListLoader
     * @throws \Exception
     */
    public function __construct()
    {
        $Headers = new Headers();
        $this->ListLoader = array_merge([
            "Libraries",
            $Headers->APIName
        ], \APIDefault::ProjectFilesOnAutoloader);
        $files = $this->getFiles();
        foreach ($files as $file) {
            include_once $file;
        }

    }

}


spl_autoload_register(function () {
    new Autoloader();
});