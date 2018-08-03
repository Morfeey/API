<?php
/**
 * Created by PhpStorm.
 * User: Burkovnikov
 * Date: 025 25.07.18
 * Time: 12:33
 */

namespace API\Libraries;


interface IStr
{
    const UpFirstChar = 0;
    const UpFistCharAndLowOther = 1;
    const LowFirstChar = 2;
    const LowFirstCharAndUpOther = 3;
    const Custom = 4;
}