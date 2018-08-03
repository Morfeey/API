<?php

namespace API\REST {
    use API\Libraries\Str;
    use API\REST\Versions;

    class Main {

        public $Headers;

        public function __construct() {

            $this->Headers = new Headers();
            $str = new Str("");
            $VersionWord = str_replace(" ", "", $str->intToWord( $this->Headers->Version ));
            $VersionAPI = "API\\REST\\Versions\\$VersionWord\\Main";
            $ActualClassAPI = new $VersionAPI();
            print $ActualClassAPI->Result();
        }
    }
}
