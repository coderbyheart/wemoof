<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use PhpOption\Option;
use PhpOption\Some;
use Symfony\Component\Form\DataTransformerInterface;


class OptionToScalarTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        /** @var Option $value */
        return $value->isEmpty() ? null : (string)$value->get();
    }

    public function reverseTransform($value)
    {
        return Some::create($value);
    }
}