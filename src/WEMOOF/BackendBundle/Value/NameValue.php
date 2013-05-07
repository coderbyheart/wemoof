<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * A name valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class NameValue implements ValueObject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($name)
    {
        $filtered_name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);
        if ($name !== $filtered_name) throw new ValueException("Name contains invalid characters.");
        $this->name = $filtered_name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param $string
     * @return EmailValue
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public static function parse($string)
    {
        return new self($string);
    }
}