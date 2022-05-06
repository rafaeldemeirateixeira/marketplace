<?php

namespace App\Types\Geo;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use App\Types\Geo\Point;

class GeometryType extends Type
{
    const GEOMETRY = 'GEOMETRY';
    const SRID = 4326;

    public function getName()
    {
        return self::GEOMETRY;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'geometry';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        list($longitude, $latitude) = sscanf($value, 'POINT(%f %f)');

        return new Point($latitude, $longitude);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Point) {
            $value = sprintf(
                "ST_GeomFromText('POINT(%F %F)', %s)",
                $value->getLongitude(),
                $value->getLatitude(),
                self::SRID
            );
        }

        return $value;
    }
}
