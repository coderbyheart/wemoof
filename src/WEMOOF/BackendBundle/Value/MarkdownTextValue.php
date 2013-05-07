<?php

namespace WEMOOF\BackendBundle\Value;

use WEMOOF\BackendBundle\Exception\ValueException;

/**
 * A markdown text valueobject
 *
 * @package WEMOOF\BackendBundle\Value
 */
class MarkdownTextValue implements ValueObject
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     * @throws \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function __construct($text)
    {
        $filtered_text = filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);
        if ($text !== $filtered_text) throw new ValueException("Name contains invalid characters.");
        $this->text = $filtered_text;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
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