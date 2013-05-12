<?php

/**
 * @author Markus Tacker <m@coderbyheart.de>
 */

namespace WEMOOF\WebBundle;

class Slugger
{
    public function slugify($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/ [-–—] /', '-', $str);
        $str = str_replace(' ', '-', $str);
        $str = iconv("UTF-8", "ASCII//TRANSLIT", $str);
        $str = preg_replace('/[^a-z0-9-]/', '', $str);
        return $str;
    }
}