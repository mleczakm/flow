<?php

declare(strict_types=1);

namespace Flow\ETL\Adapter\XML\Loader;

use Flow\ETL\Adapter\XML\RowsNormalizer\EntryNormalizer;
use Flow\ETL\Adapter\XML\RowsNormalizer\EntryNormalizer\PHPValueNormalizer;
use Flow\ETL\Adapter\XML\{RowsNormalizer, XMLWriter};
use Flow\ETL\Loader\Closure;
use Flow\ETL\{FlowContext, Loader, Rows};
use Flow\Filesystem\{DestinationStream, Partition, Path};

final class XMLLoader implements Closure, Loader, Loader\FileLoader
{
    private string $attributePrefix = '_';

    private string $dateTimeFormat = 'Y-m-d\TH:i:s.uP';

    private string $listElementName = 'element';

    private string $mapElementKeyName = 'key';

    private string $mapElementName = 'element';

    private string $mapElementValueName = 'value';

    private string $rootElementName = 'root';

    private string $rowElementName = 'row';

    /**
     * @var array<string, int>
     */
    private array $writes = [];

    /**
     * @var array<string, string>
     */
    private array $xmlAttributes = ['version' => '1.0', 'encoding' => 'UTF-8'];

    public function __construct(
        private readonly Path $path,
        private readonly XMLWriter $xmlWriter,
    ) {
    }

    public function closure(FlowContext $context) : void
    {
        foreach ($context->streams()->listOpenStreams($this->path) as $stream) {
            $stream->append('</' . $this->rootElementName . '>');
        }

        $context->streams()->closeStreams($this->path);
    }

    public function destination() : Path
    {
        return $this->path;
    }

    public function load(Rows $rows, FlowContext $context) : void
    {
        $normalizer = new RowsNormalizer(
            new EntryNormalizer(
                new PHPValueNormalizer(
                    $context->config->caster(),
                    $this->attributePrefix,
                    $this->dateTimeFormat,
                    $this->listElementName,
                    $this->mapElementName,
                    $this->mapElementKeyName,
                    $this->mapElementValueName
                ),
            ),
            $this->rowElementName
        );

        $this->write($rows, $rows->partitions()->toArray(), $context, $normalizer);
    }

    public function withAttributePrefix(string $attributePrefix) : self
    {
        $this->attributePrefix = $attributePrefix;

        return $this;
    }

    public function withDateTimeFormat(string $dateTimeFormat) : self
    {
        $this->dateTimeFormat = $dateTimeFormat;

        return $this;
    }

    public function withListElementName(string $listElementName) : self
    {
        $this->listElementName = $listElementName;

        return $this;
    }

    public function withMapElementKeyName(string $mapElementKeyName) : self
    {
        $this->mapElementKeyName = $mapElementKeyName;

        return $this;
    }

    public function withMapElementName(string $mapElementName) : self
    {
        $this->mapElementName = $mapElementName;

        return $this;
    }

    public function withMapElementValueName(string $mapElementValueName) : self
    {
        $this->mapElementValueName = $mapElementValueName;

        return $this;
    }

    public function withRootElementName(string $rootElementName) : self
    {
        $this->rootElementName = $rootElementName;

        return $this;
    }

    public function withRowElementName(string $rowElementName) : self
    {
        $this->rowElementName = $rowElementName;

        return $this;
    }

    /**
     * @param array<string, string> $xmlAttributes
     */
    public function withXMLAttributes(array $xmlAttributes) : self
    {
        $this->xmlAttributes = $xmlAttributes;

        return $this;
    }

    /**
     * @param array<Partition> $partitions
     */
    public function write(Rows $nextRows, array $partitions, FlowContext $context, RowsNormalizer $normalizer) : void
    {
        $streams = $context->streams();

        if (!$streams->isOpen($this->path, $partitions)) {
            $stream = $streams->writeTo($this->path, $partitions);

            if (!\array_key_exists($stream->path()->path(), $this->writes)) {
                $this->writes[$stream->path()->path()] = 0;
            }

            $xmlAttributes = \implode(' ', \array_map(fn (string $key, string $value) => $key . '="' . $value . '"', \array_keys($this->xmlAttributes), \array_values($this->xmlAttributes)));

            $stream->append('<?xml ' . $xmlAttributes . "?>\n<" . $this->rootElementName . ">\n");
        } else {
            $stream = $streams->writeTo($this->path, $partitions);
        }

        $this->writeXML($nextRows, $stream, $normalizer);
    }

    /**
     * @param Rows $rows
     * @param DestinationStream $stream
     */
    public function writeXML(Rows $rows, DestinationStream $stream, RowsNormalizer $normalizer) : void
    {
        if (!\count($rows)) {
            return;
        }

        foreach ($normalizer->normalize($rows) as $node) {
            $stream->append($this->xmlWriter->write($node) . "\n");
        }

        $this->writes[$stream->path()->path()]++;
    }
}
