<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.06.2018
 * Time: 9:03
 */

namespace API\Libraries\Path;

use API\Libraries\Path\Subject\SearchOption;

include_once "File.php";
include_once "Subject/SearchOption.php";

///include_once "../Str.php";


class Directory
{

    public $Directory;

    /** DOCUMENT_ROOT
     * @return string
     */
    public static function getDocumentRoot(): string
    {
        return $_SERVER["DOCUMENT_ROOT"];
    }

    /**
     * Search Directories  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param SearchOption|null $searchOption
     * @return Directory[]
     * @throws \Exception
     */
    public function getDirectories(string $Pattern = "*", SearchOption $searchOption = null): array
    {
        $searchOption = (is_null($searchOption)) ? SearchOption::Current() : $searchOption;
        $Cur = $this;
        $GetDirectories = function (string $Directory) use (&$GetDirectories, $Pattern, $searchOption, $Cur) {
            $result = [];
            $Directory = $this->getLastSlashCustom($Directory);
            $List = glob($Directory . $Pattern, GLOB_BRACE | GLOB_MARK | GLOB_ONLYDIR);

            foreach ($List as $Item) {
                $Item = $this->deleteLastSlashCustom($Item);
                array_push($result, $Item);
                $Temp = $GetDirectories($Item);
                if ($searchOption->IsRecurse && count($Temp) != 0) {
                    foreach ($Temp as $Item) {
                        array_push($result, $Item);
                    }
                }

            }

            return $result;
        };
        $result = $GetDirectories($this->Directory);

        return $result;
    }

    /** exit to the parent directory
     * @return Directory
     */
    public function parent(): self
    {
        $ListLevels = explode("/", $this->Directory);
        unset($ListLevels[count($ListLevels) - 1]);
        $result = "";
        foreach ($ListLevels as $level) {
            $result .= $level . "/";
        }

        $this->Directory = $this->deleteLastSlashCustom($result);
        return $this;
    }

    /** merging of paths
     * @param string $MergedDirectory
     * @param string|null $Directory
     * @return Directory
     */
    public function merge(string $MergedDirectory, string $Directory = null): self
    {
        $Directory = (is_null($Directory)) ? $this->Directory : $Directory;
        $this->Directory = $this->getLastSlashCustom($Directory) . $MergedDirectory;
        return $this;
    }

    /**
     * Search Files  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param SearchOption|SearchOption::Current $searchOption
     * @return File[]
     */
    public function getFiles(string $Pattern = "*", SearchOption $searchOption = null): array
    {
        $searchOption = (is_null($searchOption)) ? SearchOption::Current() : $searchOption;

        $GetFiles = function (string $Directory) use (&$GetFiles, $Pattern, $searchOption) {
            $result = [];
            $Dir = $this->getLastSlashCustom($Directory);

            $Directories = glob("$Dir*", GLOB_ONLYDIR | GLOB_MARK);
            $Files = function () use ($Dir, $Pattern) {
                $result = [];
                $files = glob($Dir . $Pattern, GLOB_BRACE);
                foreach ($files as $Item) {
                    if (is_file($Item)) {
                        array_push($result, $Item);
                    }
                }
                return $result;
            };
            $result = array_merge($result, $Files());

            if ($searchOption->IsRecurse) {
                foreach ($Directories as $Item) {
                    $result = array_merge($result, $GetFiles($Item));
                }
            }

            return $result;
        };

        $result = $GetFiles($this->Directory, $Pattern);
        return $result;
    }

    public function GetLastDir(): string
    {
        $exp = explode("/", $this->Directory);
        $result = $exp[count($exp) - 1];
        return $result;
    }

    /** Directory Info
     * @param string|null $DateFormat
     * @return Info
     * @throws \Exception
     */
    public function getInfo(string $DateFormat = null): Info
    {
        return new Info($this->Directory, $DateFormat);
    }

    public function getLastSlash(): self
    {
        $this->Directory = $this->getLastSlashCustom($this->Directory);
        return $this;
    }

    public function getLastSlashCustom(string $Directory = null)
    {
        $Directory = (is_null($Directory)) ? $this->Directory : $Directory;
        $Directory = str_replace("\\", "/", $Directory);
        $Directory = rtrim($Directory, "/");
        $result = $Directory . "/";
        return $result;
    }

    public function deleteLastSlash(): self
    {
        $this->Directory = $this->deleteLastSlashCustom($this->Directory);
        return $this;
    }

    public function deleteLastSlashCustom(string $Directory = null)
    {
        $Directory = (is_null($Directory)) ? $this->Directory : $Directory;
        $Directory = str_replace("\\", "/", $Directory);
        $result = rtrim($Directory, "/");
        return $result;
    }

    /**
     * Directory constructor.
     * @param $Directories
     * @throws \Exception
     */
    public function __construct($Directories)
    {
        $params = func_get_args();
        if (!is_null($params) && count($params) != 0) {
            foreach ($params as $ItemDirectory) {
                $this->Directory .= $this->getLastSlashCustom($ItemDirectory);
            }
            $this->Directory = $this->deleteLastSlashCustom($this->Directory);
        } else {
            $print = print_r($params, true);
            throw new \Exception("Count parameters is empty: $print", 601);
        }
    }
}