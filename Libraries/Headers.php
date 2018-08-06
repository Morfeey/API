<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 12:51
 */

namespace API\Libraries;

use API\Libraries\Subject\Header;

include_once __DIR__."/Subject/Header.php";
include_once "HeaderConfiguration/Head.php";
include_once "Str.php";
include_once "Path/Directory.php";


class Headers
{
    public $APIName;
    public $Version;
    public $Content_type;
    public $Language;
    public $Readiness;
    private $ToSend = [];
    private $SystemExceptions = ["SystemExceptions", "ToSend"];

    public function addToSendHeader(Header $header): self
    {
        array_push($this->ToSend, $header);
        return $this;
    }

    public function addToSendArray(array $ListKeyValue): self
    {
        foreach ($ListKeyValue as $key => $value) {
            $this->addToSendStrings($key, $value);
        }
        return $this;
    }

    public function addToSendStrings($key, $value): self
    {
        $header = new Header();
        $header
            ->setKey($key)
            ->setValue($value);
        $this->addToSendHeader($header);
        return $this;
    }

    public function send(): self
    {
        foreach ($this->ToSend as $header) {
            \header($header->getKey() . ":" . $header->getValue());
        }
        return $this;
    }

    public function getFullList(): array
    {
        return getallheaders();
    }

    public function getUserAssigned(): array
    {
        $all = $this->getFullList();
        $needle = $this->getCleanedClassVars();

//        foreach ($needle as $key=>$value) {
//            if (key_exists($key, $all)) {
//
//            }
//        }

        return $needle;
    }


    /**
     * Prepare to send default values
     * @return Headers
     */
    private function prepareToSend(): self
    {
        foreach ($this->getCleanedClassVars() as $field) {
            $this->addToSendStrings($field, $this->$field);
        }
        return $this;
    }

    private function getCleanedClassVars(): array
    {
        $classVars = array_keys(get_class_vars(__CLASS__));
        foreach ($this->SystemExceptions as $exception) {
            unset($classVars[array_search($exception, $classVars)]);
        }
        return $classVars;
    }

    private function setValues()
    {
        $All = $this->getFullList();
        $AllCorrect = [];
        $CurrentFields = $this->getCleanedClassVars();


        foreach ($All as $key => $value) {
            $strKey = new Str($key);
            $key = str_replace("-", "_", $strKey->fistCharUpAllWords(["_", "-"])->String);
            $AllCorrect[$key] = $value;
        }

        foreach ($CurrentFields as $field) {

            if (isset($AllCorrect[$field])) {
                $this->$field = $AllCorrect[$field];
            } else {
                if ($field != "SystemExceptions" && $field != "ToSend") {
                    $this->$field = constant("APIDefault::$field");
                }
            }

        }
        return $this;
    }

    public function __construct()
    {
        $this
            ->setValues()
            ->prepareToSend();

    }
}