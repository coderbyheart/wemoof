<?php

namespace WEMOOF\BackendBundle\Doctrine\Type;

use Carbon\Carbon;
use PhpOption\Option;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class CarbonOptionalType extends DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) return \PhpOption\None::create();
        return \PhpOption\Option::fromValue(new Carbon($value));
    }

    public function getName()
    {
        return 'carbon_optional';
    }
}