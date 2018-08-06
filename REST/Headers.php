<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.06.2018
 * Time: 15:18
 */

namespace API\REST;
use API\Libraries\Str;


class Headers
{
    public $Version = "last";
    public $Language = "ru";
    public $Content_Type = "application/json";
    public $Readiness = "release";

    private $HeadersToSend= [];

    public function AddToSend (Header $Item) {
        array_push($this->HeadersToSend, $Item);
    }

    public function Send () {
        foreach ($this->HeadersToSend as $Item) {
            \header($Item->Name.":".$Item->Value);
        }
    }

    public function GetFullList(): array
    {
        return getallheaders();
    }

    private function SetValues () {
        $Headers = $this->GetFullList();
        $HeadersLowerKeys = [];
        foreach ($Headers as $key => $Item) {
            $key = str_replace("-", "_",  strtolower($key));
            $HeadersLowerKeys[$key] = $Item;
        }
        foreach (array_keys(get_object_vars($this)) as $Item) {

            $NewValue = $HeadersLowerKeys[strtolower($Item)];
            if ($NewValue!="" && !is_null($NewValue)) {
                $str = new Str($Item);
                $Item = $str->fistCharUpAllWords()->String;
                $this->$Item = $NewValue;
            }

        }
    }

    private function AutoPrepareToSend () {
        $AutoPrepareHeaders = get_object_vars($this);
        unset($AutoPrepareHeaders["HeadersToSend"]);
        foreach ($AutoPrepareHeaders as $header => $value) {
            $headerObject = new Header();
            $headerObject->Name = $header;
            $headerObject->Value = $value;
            $this->AddToSend($headerObject);
        }
    }

    public function __construct()
    {

        $this->SetValues();
        $Readiness = new Readiness($this);
        $this->Readiness = $Readiness->Correct();
        $Version = new Version($this);
        $this->Version = $Version->Correct();


        $this->AutoPrepareToSend();
    }
}