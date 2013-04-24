<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m
 * Date: 21.04.13
 * Time: 13:25
 * To change this template use File | Settings | File Templates.
 */

namespace WEMOOF\BackendBundle\Value;


interface ValueObject
{
    public function __toString();
    public static function parse($string);
}