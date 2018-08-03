<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.06.2018
 * Time: 11:17
 */

namespace API\Libraries\Path;
include_once "File.php";


class File
{
    public $File;
    public $Directory;
    public $Name;
    public $NameWithoutExtension;
    public $Extension;

    public function getNameSpaceAndClassOnName () {
        $ReplacedDirectory = str_replace($_SERVER["DOCUMENT_ROOT"], "", $this->Directory);
        $Directory = new Directory($ReplacedDirectory);
        $ReplacedDirectory = $Directory->getLastSlashCustom();
        $ReplacedDirectory = str_replace("/", "\\", $ReplacedDirectory);
        $result = $ReplacedDirectory.$this->NameWithoutExtension;
        return $result;
    }
    public function getInfo ($DateFormat = null): Info {
        return new Info($this->File, $DateFormat);
    }
    public function __construct(string $File) {
        if (!is_null($File) && $File!="" && is_file($File)) {
            $this->File = $File;
            $PathInfo = pathinfo($File);
            $this->Name = $PathInfo["basename"];
            $this->NameWithoutExtension = $PathInfo["filename"];
            $this->Extension = $PathInfo["extension"];
            $this->Directory = $PathInfo["dirname"];
        }else {
            throw new \Exception("Incorrect file \"$File\"", 601);
        }
    }
}