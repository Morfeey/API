<?php
include_once "Autoloader.php";
include_once "Libraries/Headers.php";
//new API\REST\Main();
$Headers = new API\Libraries\Headers();
$Versions = new \API\Libraries\HeaderConfiguration\Version();
//var_dump($Versions);
$Headers->send();
var_dump($Headers);


print_r($Headers->getUserAssigned());

