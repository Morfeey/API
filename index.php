<?php
include_once "Autoloader.php";
include_once "Libraries/Headers.php";
//new API\REST\Main();
$Headers = new API\Libraries\Headers();
$Versions = new \API\Libraries\HeaderConfiguration\Version();
var_dump($Versions);


$Direcrory = new \API\Libraries\Path\Directory(__DIR__);
print "<pre>";
print_r($Direcrory->getDirectories("*", \API\Libraries\Path\Subject\SearchOption::Recurse()));
print "</pre>";