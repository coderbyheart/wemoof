<?php

namespace WEMOOF\WebBundle\Twig\Extension;

class CompactUrl extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'compacturl' => new \Twig_Filter_Method($this, 'compacturl', array('is_safe' => array('html')))
        );
    }

    public function compacturl($str)
    {
        return preg_replace('/^www\./', '', parse_url($str, PHP_URL_HOST));
    }

    public function getName()
    {
        return 'wemoof_webbundle_twig_extension_compacturl';
    }
}