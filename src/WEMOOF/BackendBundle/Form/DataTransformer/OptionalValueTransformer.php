<?php

namespace WEMOOF\BackendBundle\Form\DataTransformer;

use PhpOption\Option;
use PhpOption\Some;
use PhpOption\None;
use Symfony\Component\Form\DataTransformerInterface;

class OptionalValueTransformer implements DataTransformerInterface
{
    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    private $valueTransformer;

    /**
     * @param DataTransformerInterface $valueTransformer
     */
    public function __construct(DataTransformerInterface $valueTransformer)
    {
        $this->valueTransformer = $valueTransformer;
    }

    public function transform($value)
    {
        /** @var Option $value */
        return $value->isEmpty() ? null : $this->valueTransformer->transform($value->get());
    }

    public function reverseTransform($value)
    {
        return $value === null ? None::create() : Some::create($this->valueTransformer->reverseTransform($value));
    }
}