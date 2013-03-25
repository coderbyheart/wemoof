<?php

namespace WEMOOF\WebBundle\Twig\Extension;

class Date extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'shortdate' => new \Twig_Filter_Method($this, 'date', array('is_safe' => array('html'))),
            'longdate' => new \Twig_Filter_Method($this, 'longdate', array('is_safe' => array('html'))),
        );
    }

    public function date(\DateTime $d, $format = "%d.%m.%Y")
    {
        return strftime($format, $d->getTimestamp());
    }

    public function longdate(\DateTime $d)
    {
        return $this->date($d, '%A, den %d.%m.%Y um %H:%M Uhr');
    }

    public function getName()
    {
        return 'wemoof_webbundle_twig_extension_date';
    }
}