<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use WEMOOF\BackendBundle\Value\TwitterHandleValue;

class TwitterHandleValueToScalarTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        /** @var TwitterHandleValue $value */
        return (string)$value;
    }

    public function reverseTransform($value)
    {
        return TwitterHandleValue::parse($value);
    }
}