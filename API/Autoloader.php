<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.06.2018
 * Time: 10:01
 */

namespace API;

include_once "Libraries/Path/Directory.php";
include_once "Libraries/Path/Subject/SearchOption.php";

use API\Libraries\Path\Directory;
use API\Libraries\Path\Subject\SearchOption;

class Autoloader
{
    private $ListLoader;

    private function GetFiles(): array
    {
        $result = [];
        foreach ($this->ListLoader as $LoadItem) {
            $Directory = new Directory(__DIR__, $LoadItem);
            $files = $Directory->GetFiles("*.php", SearchOption::Recurse());
            $result = array_merge($result, $files);
        }
        return $result;
    }

    private function GetClasses()
    {
        $result = [];
        $CurrentDirectory = str_replace("\\", "/", __DIR__);
        foreach ($this->GetFiles() as $file) {
            $file = str_replace($CurrentDirectory, "", $file->Directory . $file->NameWithoutExtension);
            $class = __NAMESPACE__ . str_replace("/", "\\", $file);
            array_push($result, $class);
        }

        return $result;
    }

    public function __construct(array $ListLoader = null)
    {
        $this->ListLoader = (is_null($ListLoader)) ? [
            "Libraries",
            "REST"
        ] : $ListLoader;
        $files = $this->GetFiles();
        foreach ($files as $file) {
            include_once $file->File;
        }

    }

}

spl_autoload_register(function () {
    new Autoloader();
});