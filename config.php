<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 016 16.07.18
 * Time: 13:01
 */

interface APIDefault {
    /* Directory API name */
    const APIName = "REST";
    /* Directories in the project that you want to connect with the *.php extension.  */
    const ProjectFilesOnAutoloader = [];
    const Version = "last";
    const Language = "ru";
    /* Accepted as content-type */
    const Content_type = "application/json";
    const Readiness = "release";
}