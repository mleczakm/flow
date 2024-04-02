<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Unit\Function;

use function Flow\ETL\DSL\{float_entry, int_entry, ref, str_entry};
use Flow\ETL\Row;
use PHPUnit\Framework\TestCase;

final class MaxTest extends TestCase
{
    public function test_aggregation_max_from_numeric_values() : void
    {
        $aggregator = \Flow\ETL\DSL\max(ref('int'));

        $aggregator->aggregate(Row::create(str_entry('int', '10')));
        $aggregator->aggregate(Row::create(str_entry('int', '20')));
        $aggregator->aggregate(Row::create(str_entry('int', '55')));
        $aggregator->aggregate(Row::create(str_entry('int', '25')));
        $aggregator->aggregate(Row::create(str_entry('not_int', null)));

        self::assertSame(
            55,
            $aggregator->result()->value()
        );
    }

    public function test_aggregation_max_including_null_value() : void
    {
        $aggregator = \Flow\ETL\DSL\max(ref('int'));

        $aggregator->aggregate(Row::create(int_entry('int', 10)));
        $aggregator->aggregate(Row::create(int_entry('int', 20)));
        $aggregator->aggregate(Row::create(int_entry('int', 30)));
        $aggregator->aggregate(Row::create(str_entry('int', null)));

        self::assertSame(
            30,
            $aggregator->result()->value()
        );
    }

    public function test_aggregation_max_with_float_result() : void
    {
        $aggregator = \Flow\ETL\DSL\max(ref('int'));

        $aggregator->aggregate(Row::create(int_entry('int', 10)));
        $aggregator->aggregate(Row::create(int_entry('int', 20)));
        $aggregator->aggregate(Row::create(float_entry('int', 30.5)));
        $aggregator->aggregate(Row::create(int_entry('int', 25)));

        self::assertSame(
            30.5,
            $aggregator->result()->value()
        );
    }

    public function test_aggregation_max_with_integer_result() : void
    {
        $aggregator = \Flow\ETL\DSL\max(ref('int'));

        $aggregator->aggregate(Row::create(int_entry('int', 10)));
        $aggregator->aggregate(Row::create(int_entry('int', 20)));
        $aggregator->aggregate(Row::create(int_entry('int', 30)));
        $aggregator->aggregate(Row::create(int_entry('int', 40)));

        self::assertSame(
            40,
            $aggregator->result()->value()
        );
    }
}
