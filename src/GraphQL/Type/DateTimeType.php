<?php

namespace App\GraphQL\Type;

class DateTimeType
{
    public static function serialize(\DateTimeInterface $value): string
    {
        return $value->format('Y-m-d H:i:s');
    }
    public static function parseValue(string $value = null)
    {
        if (!$value) {
            return null;
        }
        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
    }
    public static function parseLiteral($valueNode): \DateTimeInterface
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $valueNode->value);
    }
}