<?php

declare(strict_types=1);

namespace Flow\Parquet\ParquetFile\Schema;

final readonly class MapKey
{
    private function __construct(public Column $key)
    {
    }

    public static function boolean() : self
    {
        return new self(FlatColumn::boolean('key')->makeRequired());
    }

    public static function date() : self
    {
        return new self(FlatColumn::date('key')->makeRequired());
    }

    public static function datetime() : self
    {
        return new self(FlatColumn::dateTime('key')->makeRequired());
    }

    public static function decimal(int $precision, int $scale) : self
    {
        return new self(FlatColumn::decimal('key', $scale, $precision)->makeRequired());
    }

    public static function double() : self
    {
        return new self(FlatColumn::double('key')->makeRequired());
    }

    public static function float() : self
    {
        return new self(FlatColumn::float('key')->makeRequired());
    }

    public static function int32() : self
    {
        return new self(FlatColumn::int32('key')->makeRequired());
    }

    public static function int64() : self
    {
        return new self(FlatColumn::int64('key')->makeRequired());
    }

    public static function string() : self
    {
        return new self(FlatColumn::string('key')->makeRequired());
    }

    public static function time() : self
    {
        return new self(FlatColumn::time('key')->makeRequired());
    }

    public static function uuid() : self
    {
        return new self(FlatColumn::uuid('key')->makeRequired());
    }
}
