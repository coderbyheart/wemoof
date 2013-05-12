<?php

namespace WEMOOF\WebBundle\Twig\Extension;

use WEMOOF\WebBundle\Slugger;

class Slug extends \Twig_Extension
{
    /**
     * @var Slugger
     */
    private $slugger;


    public function getFilters()
    {
        return array(
            'slug' => new \Twig_Filter_Method($this, 'slugify', array('is_safe' => array('html')))
        );
    }

    public function slugify($str)
    {
        if ($this->slugger === null) $this->slugger = new Slugger();
        return $this->slugger->slugify($str);
    }

    public function getName()
    {
        return 'wemoof_webbundle_twig_extension_slug';
    }
}
