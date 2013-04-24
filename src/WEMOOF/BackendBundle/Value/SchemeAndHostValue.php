<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * Represents a scheme and a host, e.g. "http://some-host.com"
 * @package WEMOOF\BackendBundle\Value
 */
class SchemeAndHostValue implements ValueObject
{
    /**
     * @var string
     */
    private $schemeAndHost;

    /**
     * @param string $schemeAndHost
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($schemeAndHost)
    {
        if (filter_var($schemeAndHost, FILTER_VALIDATE_URL) === false) throw new ValueException(sprintf("Not an url: %s", $schemeAndHost));
        $parts = parse_url($schemeAndHost);
        $allowed = array('scheme', 'host', 'port');
        foreach ($parts as $type => $value) if (!in_array($type, $allowed)) throw new ValueException(sprintf("%s not allowed in scheme and host value", $type));
        $this->schemeAndHost = $schemeAndHost;
    }

    /**
     * @param $str
     * @return \WEMOOF\BackendBundle\Value\SchemeAndHostValue
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
        return $this->schemeAndHost;
    }
}