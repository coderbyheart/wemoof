<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * An email valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class EmailValue implements ValueObject
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) throw new ValueException(sprintf("Not an email address: %s", $email));
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email;
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