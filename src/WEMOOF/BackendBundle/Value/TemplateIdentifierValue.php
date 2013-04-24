<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m
 * Date: 21.04.13
 * Time: 13:19
 * To change this template use File | Settings | File Templates.
 */

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * An email valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class TemplateIdentifierValue implements ValueObject
{
    /**
     * @var string
     */
    private $templateIdentifier;

    /**
     * @param string $templateIdentifier
     */
    public function __construct($templateIdentifier)
    {
        $this->templateIdentifier = $templateIdentifier;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->templateIdentifier;
    }

    /**
     * @param $string
     * @return TemplateIdentifierValue
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public static function parse($string)
    {
        return new self($string);
    }
}