<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Unit\Join\Comparison;

use function Flow\ETL\DSL\{int_entry, row};
use Flow\ETL\Join\Comparison;
use Flow\ETL\Join\Comparison\Any;
use Flow\ETL\Tests\FlowTestCase;

final class AnyTest extends FlowTestCase
{
    public function test_failure() : void
    {
        $comparison1 = self::createStub(Comparison::class);
        $comparison1
            ->method('compare')
            ->willReturn(false);

        $comparison2 = self::createStub(Comparison::class);
        $comparison2
            ->method('compare')
            ->willReturn(false);

        self::assertFalse(
            (new Any($comparison1, $comparison2))
                ->compare(
                    row(int_entry('id', 1)),
                    row(int_entry('id', 2)),
                )
        );
    }

    public function test_success() : void
    {
        $comparison1 = self::createStub(Comparison::class);
        $comparison1
            ->method('compare')
            ->willReturn(true);

        $comparison2 = self::createStub(Comparison::class);
        $comparison2
            ->method('compare')
            ->willReturn(false);

        self::assertTrue(
            (new Any($comparison1, $comparison2))
                ->compare(
                    row(int_entry('id', 1)),
                    row(int_entry('id', 2)),
                )
        );
    }
}
