<?php
include_once "Libraries/functions.php";
include_once "Autoloader.php";


use \API\Libraries\HeaderConfiguration\Versions;
use API\Libraries\Headers;
use \API\Libraries\Event;

//new API\REST\Main();

$Headers = new Headers();
$Versions = new Versions();
$File = new \API\Libraries\Path\File(__FILE__);
$Headers->send();





//print_r($Headers->getUserAssigned());
print $File->Directory;