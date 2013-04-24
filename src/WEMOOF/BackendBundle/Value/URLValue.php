<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * Represents an URL
 * @package WEMOOF\BackendBundle\Value
 */
class URLValue implements ValueObject
{
    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) throw new ValueException(sprintf("Not an url: %s", $url));
        $this->url = $url;
    }

    /**
     * @param $str
     * @return \WEMOOF\BackendBundle\Value\URLValue
     */
    public static function parse($str)
    {
        return new self($str);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->url;
    }
}