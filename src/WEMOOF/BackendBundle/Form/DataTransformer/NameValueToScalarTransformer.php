<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use WEMOOF\BackendBundle\Value\NameValue;

class NameValueToScalarTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        /** @var NameValue $value */
        return (string)$value;
    }

    public function reverseTransform($value)
    {
        return NameValue::parse($value);
    }
}