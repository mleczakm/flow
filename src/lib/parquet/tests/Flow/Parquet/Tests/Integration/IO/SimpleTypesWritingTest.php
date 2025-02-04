<?php

declare(strict_types=1);

namespace Flow\Parquet\Tests\Integration\IO;

use function Flow\ETL\DSL\{generate_random_int, generate_random_string};
use Faker\Factory;
use Flow\Parquet\ParquetFile\Schema;
use Flow\Parquet\ParquetFile\Schema\FlatColumn;
use Flow\Parquet\{Consts, Reader, Writer};
use PHPUnit\Framework\TestCase;

final class SimpleTypesWritingTest extends TestCase
{
    protected function setUp() : void
    {
        if (!\file_exists(__DIR__ . '/var')) {
            \mkdir(__DIR__ . '/var');
        }
    }

    public function test_writing_bool_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::boolean('boolean'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'boolean' => (bool) $i % 2 == 0,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertSame(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_bool_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::boolean('boolean'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'boolean' => $i % 2 == 0 ? (bool) generate_random_int(0, 1) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertSame(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_date_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::date('date'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'date' => \DateTimeImmutable::createFromMutable($faker->dateTimeThisYear)->setTime(0, 0, 0, 0),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_date_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::date('date'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'date' => $i % 2 === 0 ? \DateTimeImmutable::createFromMutable($faker->dateTimeThisYear)->setTime(0, 0, 0, 0) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_decimal_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::decimal('decimal'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'decimal' => \round($faker->randomFloat(5), 2),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_decimal_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::decimal('decimal'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'decimal' => $i % 2 === 0 ? \round($faker->randomFloat(5), 2) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_double_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::double('double'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'double' => $faker->randomFloat(),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_double_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::double('double'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'double' => $i % 2 === 0 ? $faker->randomFloat() : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_enum_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::enum('enum'));

        $enum = ['A', 'B', 'C', 'D'];

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'enum' => $enum[generate_random_int(0, 3)],
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_float_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::float('float'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'float' => 10.25,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_float_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::float('float'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'float' => $i % 2 === 0 ? 10.25 : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_int32_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::int32('int32'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'int32' => $faker->numberBetween(0, Consts::PHP_INT32_MAX),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_int32_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::int32('int32'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'int32' => $i % 2 === 0 ? $faker->numberBetween(0, Consts::PHP_INT32_MAX) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );

        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_int64() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::int64('int64'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'int64' => $faker->numberBetween(0, Consts::PHP_INT64_MAX),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_int64_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::int64('int64'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'int64' => $i % 2 === 0 ? $faker->numberBetween(0, Consts::PHP_INT64_MAX) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_json_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::json('json'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'json' => \json_encode(['street' => $faker->streetName, 'city' => $faker->city, 'country' => $faker->country, 'zip' => $faker->postcode], JSON_THROW_ON_ERROR),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_json_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::json('json'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'json' => $i % 2 === 0
                    ? \json_encode(['street' => $faker->streetName, 'city' => $faker->city, 'country' => $faker->country, 'zip' => $faker->postcode], JSON_THROW_ON_ERROR)
                    : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_string_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::string('string'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'string' => $faker->text(50),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_string_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::string('string'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'string' => $i % 2 === 0 ? $faker->text(50) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_time_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::time('time'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'time' => (new \DateTimeImmutable('2023-01-01 00:00:00 UTC'))->diff(new \DateTimeImmutable('2023-01-01 15:45:00 UTC')),
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_time_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::time('time'));

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'time' => $i % 2 === 0 ? (new \DateTimeImmutable('2023-01-01 00:00:00 UTC'))->diff(new \DateTimeImmutable('2023-01-01 15:45:00 UTC')) : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_timestamp_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::dateTime('dateTime'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'dateTime' => $faker->dateTimeThisYear,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_timestamp_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::dateTime('dateTime'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'dateTime' => $i % 2 === 0 ? $faker->dateTimeThisYear : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_uuid_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::uuid('uuid'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'uuid' => $faker->uuid,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }

    public function test_writing_uuid_nullable_column() : void
    {
        $path = __DIR__ . '/var/test-writer-parquet-test-' . generate_random_string() . '.parquet';

        $writer = new Writer();
        $schema = Schema::with(FlatColumn::uuid('uuid'));

        $faker = Factory::create();

        $inputData = \array_merge(...\array_map(static fn (int $i) : array => [
            [
                'uuid' => $i % 2 === 0 ? $faker->uuid : null,
            ],
        ], \range(1, 100)));

        $writer->write($path, $schema, $inputData);

        self::assertEquals(
            $inputData,
            \iterator_to_array((new Reader())->read($path)->values())
        );
        self::assertTrue(\file_exists($path));
        \unlink($path);
    }
}
