<?php

namespace WEMOOF\BackendBundle\Doctrine\Type;

use Carbon\Carbon;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class CarbonType extends DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) return null;
        return new Carbon($value);
    }

    public function getName()
    {
        return 'carbon';
    }
}