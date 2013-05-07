<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use WEMOOF\BackendBundle\Value\MarkdownTextValue;

class MarkdownTextValueToScalarTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        /** @var MarkdownTextValue $value */
        return (string)$value;
    }

    public function reverseTransform($value)
    {
        return MarkdownTextValue::parse($value);
    }
}