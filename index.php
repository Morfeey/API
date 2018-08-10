<?php
include_once "Autoloader.php";
include_once "Libraries/Headers.php";
//new API\REST\Main();
$Headers = new API\Libraries\Headers();
$Versions = new \API\Libraries\HeaderConfiguration\Versions();
//var_dump($Versions);
$Headers->send();


//include_once "config.php";
//include_once __DIR__."/Libraries/HeaderConfiguration/Subject/Version.php";
//include_once __DIR__."/Libraries/HeaderConfiguration/Versions.php";
//include_once __DIR__."/Libraries/Path/Directory.php";
//
//use \API\Libraries\HeaderConfiguration\Subject\Version;
//use \API\Libraries\HeaderConfiguration\Versions;
//use \API\Libraries\Path\Directory;
//
//$Versions = new Versions();
//
//print $Versions->getDirectoryCurrentVersion()."<br>";
//
//var_dump($Versions);