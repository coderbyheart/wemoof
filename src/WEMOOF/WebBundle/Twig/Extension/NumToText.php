<?php

namespace WEMOOF\WebBundle\Twig\Extension;

class NumToText extends \Twig_Extension
{
    private $prenomTexts = array(
        1 => 'erste',
        2 => 'zweite',
        3 => 'dritte',
        4 => 'vierte',
        5 => 'fünfte',
        6 => 'sechste',
    );

    private $texts = array(
        1 => 'eins',
        2 => 'zwei',
        3 => 'drei',
        4 => 'vier',
        5 => 'fünf',
        6 => 'sechs',
    );

    public function getFilters()
    {
        return array(
            'numtotext' => new \Twig_Filter_Method($this, 'numtotext', array('is_safe' => array('html')))
        );
    }

    public function numtotext($num)
    {
        $num = strval($num);
        return substr($num, -1) === '.' ? $this->toPrenomText(intval(substr($num, 0, -1))) : $this->toText(intval($num));
    }

    protected function toPrenomText($num)
    {
        return isset($this->prenomTexts[$num]) ? $this->prenomTexts[$num] : sprintf("%d.", $num);
    }

    protected function toText($num)
    {
        return isset($this->texts[$num]) ? $this->texts[$num] : $num;
    }

    public function getName()
    {
        return 'wemoof_webbundle_twig_extension_numtotext';
    }
}