<?php

namespace WEMOOF\WebBundle\Twig\Extension;

class Slug extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'slug' => new \Twig_Filter_Method($this, 'slug', array('is_safe' => array('html')))
        );
    }

    public function slug($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/ [-–—] /', '-', $str);
        $str = str_replace(' ', '-', $str);
        $str = iconv("UTF-8", "ASCII//TRANSLIT", $str);
        $str = preg_replace('/[^a-z0-9-]/', '', $str);
        return $str;
    }

    public function getName()
    {
        return 'wemoof_webbundle_twig_extension_slug';
    }
}
