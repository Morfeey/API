<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.06.2018
 * Time: 12:24
 */

namespace API\Libraries\Path;


use API\Libraries\Path\Subject\SearchOption;

class Info{

    public $Size;
    public $SizeString;
    public $LastAccess;
    public $LastModified;
    public $CreationTime;
    public $LastAccessUnix;
    public $LastModifiedUnix;
    public $CreationTimeUnix;

    public function GetSizeString() {
        $ListValues = ["byte", "kb", "mb", "gb", "tb", "pb"];
        $i = 0;
        $Size = $this->Size;

        label:
        if ($Size > 1024 && $i < count($ListValues)) {
            $Size = $this->Size / 1024;
            $i++;
            goto label;
        }

        $result = round($Size, 2) . " " . $ListValues[$i];
        return $result;
    }

    /**
     * Info constructor.
     * @param string|null $Object
     * @param string|"d.m.Y" $DateFormat
     * @throws \Exception // 602\Exception = $Object is null or empty
     */
    public function __construct(string $Object = null, string $DateFormat = null) {
        $DateFormat = (is_null($DateFormat)) ? "d.m.Y": $DateFormat;
        if (!is_null($Object) && $Object!="") {
            clearstatcache(true, $Object);
            $Info = (is_file($Object)) ? stat($Object): lstat($Object);

            if (is_file($Object)) {
                $this->Size = (int) $Info[7];
            }else if (is_dir($Object)) {
                $Directory = new Directory($Object);
                foreach ($Directory->GetFiles("*", SearchOption::Recurse()) as $file) {
                    $this->Size += (int) stat($file->File)[7];
                }
            }
            $this->LastAccessUnix = $Info[8];
            $this->LastModifiedUnix = $Info[9];
            $this->CreationTimeUnix = $Info[10];

            $this->SizeString = $this->GetSizeString();
            $this->LastAccess = date($DateFormat, $this->LastAccessUnix);
            $this->LastModified = date($DateFormat, $this->LastModifiedUnix);
            $this->CreationTime = date($DateFormat, $this->CreationTimeUnix);


        }else {
            throw new \Exception("Error: String \$Object is \"$Object\"", 602);
        }
    }
}