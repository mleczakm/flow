<?php

declare(strict_types=1);

use function Flow\ETL\Adapter\CSV\from_csv;
use function Flow\ETL\DSL\{data_frame, to_stream};

require __DIR__ . '/../../../autoload.php';

data_frame()
    ->read(from_csv(__DIR__ . '/input/color=*/sku=*/*.csv'))
    ->write(to_stream(__DIR__ . '/output.txt', truncate: false))
    ->run();
