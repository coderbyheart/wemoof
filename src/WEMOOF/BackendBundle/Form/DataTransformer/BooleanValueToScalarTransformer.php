<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use WEMOOF\BackendBundle\Value\BooleanValue;

class BooleanValueToScalarTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        /** @var BooleanValue $value */
        return $value->getBoolean();
    }

    public function reverseTransform($value)
    {
        return BooleanValue::parse($value);
    }
}