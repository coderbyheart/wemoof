<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * A Boolean valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class BooleanValue implements ValueObject
{
    /**
     * @var string
     */
    private $boolean;

    /**
     * @param string $boolean
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($boolean)
    {
        if (!is_bool($boolean)) throw new ValueException("No boolean given.");
        $this->boolean = (bool)$boolean;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->boolean ? '1' : '0';
    }

    /**
     * @return string
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * @param $string
     * @return EmailValue
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public static function parse($string)
    {
        if (!is_string($string)) throw new ValueException("No string given.");
        if ($string === 'true' || $string === '1') return new self(true);
        if ($string === 'false' || $string === '0') return new self(false);
    }

    /**
     * Creates a new false value
     *
     * @return BooleanValue
     */
    public static function false()
    {
        return new self(false);
    }

    /**
     * Creates a new true value
     *
     * @return BooleanValue
     */
    public static function true()
    {
        return new self(true);
    }
}