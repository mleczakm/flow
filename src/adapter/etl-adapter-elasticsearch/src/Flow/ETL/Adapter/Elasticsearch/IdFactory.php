<?php

declare(strict_types=1);

namespace Flow\ETL\Adapter\Elasticsearch;

use Flow\ETL\Row;
use Flow\ETL\Row\Entry;

interface IdFactory
{
    /**
     * @return Entry<mixed, mixed>
     */
    public function create(Row $row) : Entry;
}
