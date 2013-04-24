<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * An id valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class IdValue implements ValueObject
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT) === false) throw new ValueException(sprintf("Not an id: %s", $id));
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * @param $string
     * @return IdValue
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public static function parse($string)
    {
        return new self($string);
    }
}