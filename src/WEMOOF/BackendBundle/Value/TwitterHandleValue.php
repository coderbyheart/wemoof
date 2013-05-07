<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * An twitter valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class TwitterHandleValue implements ValueObject
{
    /**
     * @var string
     */
    private $twitter;

    /**
     * @param string $twitter
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($twitter)
    {
        if (!preg_match('/@[a-zA-Z0-9_]{1,15}/', $twitter)) throw new ValueException(sprintf("Not a twitter handle: %s", $twitter));
        $this->twitter = $twitter;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->twitter;
    }

    /**
     * @param $string
     * @return TwitterHandleValue
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public static function parse($string)
    {
        return new self($string);
    }
}