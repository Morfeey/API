<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 12:51
 */

namespace API\Libraries;

include_once "Str.php";
include_once "Path/Directory.php";

use API\Libraries\HeaderConfiguration\Version;

class Headers
{
    public $APIName;
    public $Version;
    public $Content_type;
    public $Language;
    public $Readiness;

    public function addToSend () {

    }
    public function send () {

    }

    public function getFullList (): array {
        return getallheaders();
    }

    private function setValues () {
        $All = self::getFullList();
        $AllCorrect = [];
        $CurrentFields = array_keys( get_class_vars(__CLASS__));

        foreach ($All as $key=>$value) {
            $strKey = new Str($key);
            $key = str_replace("-", "_", $strKey->UpFirstCharsWords(["_", "-"])->String );
            $AllCorrect[$key] = $value;
        }

        foreach ($CurrentFields as $field) {

            if (isset($AllCorrect[$field])) {
                $this->$field = $AllCorrect[$field];
            }else {
                $this->$field = constant("APIDefault::$field");
            }

        }
    }

    public function __construct()
    {
        $this->setValues();

    }
}