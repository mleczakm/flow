<?php

declare(strict_types=1);
namespace Flow\Parquet\Thrift;

/**
 * Autogenerated by Thrift Compiler (0.18.1).
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *
 *  @generated
 */
use Thrift\Exception\{TProtocolException};
use Thrift\Type\{TType};

/**
 * New page format allowing reading levels without decompressing the data
 * Repetition and definition levels are uncompressed
 * The remaining section containing the data is compressed if is_compressed is true.
 */
class DataPageHeaderV2
{
    public static $_TSPEC = [
        1 => [
            'var' => 'num_values',
            'isRequired' => true,
            'type' => TType::I32,
        ],
        2 => [
            'var' => 'num_nulls',
            'isRequired' => true,
            'type' => TType::I32,
        ],
        3 => [
            'var' => 'num_rows',
            'isRequired' => true,
            'type' => TType::I32,
        ],
        4 => [
            'var' => 'encoding',
            'isRequired' => true,
            'type' => TType::I32,
            'class' => '\Flow\Parquet\Thrift\Encoding',
        ],
        5 => [
            'var' => 'definition_levels_byte_length',
            'isRequired' => true,
            'type' => TType::I32,
        ],
        6 => [
            'var' => 'repetition_levels_byte_length',
            'isRequired' => true,
            'type' => TType::I32,
        ],
        7 => [
            'var' => 'is_compressed',
            'isRequired' => false,
            'type' => TType::BOOL,
        ],
        8 => [
            'var' => 'statistics',
            'isRequired' => false,
            'type' => TType::STRUCT,
            'class' => '\Flow\Parquet\Thrift\Statistics',
        ],
    ];

    public static $isValidate = false;

    /**
     * Length of the definition levels.
     *
     * @var int
     */
    public $definition_levels_byte_length;

    /**
     * Encoding used for data in this page *.
     *
     * @var int
     */
    public $encoding;

    /**
     * Whether the values are compressed.
     * Which means the section of the page between
     * definition_levels_byte_length + repetition_levels_byte_length + 1 and compressed_page_size (included)
     * is compressed with the compression_codec.
     * If missing it is considered compressed.
     *
     * @var bool
     */
    public $is_compressed = true;

    /**
     * Number of NULL values, in this data page.
     * Number of non-null = num_values - num_nulls which is also the number of values in the data section *.
     *
     * @var int
     */
    public $num_nulls;

    /**
     * Number of rows in this data page. Every page must begin at a
     * row boundary (repetition_level = 0): rows must **not** be
     * split across page boundaries when using V2 data pages.
     *
     * @var int
     */
    public $num_rows;

    /**
     * Number of values, including NULLs, in this data page. *.
     *
     * @var int
     */
    public $num_values;

    /**
     * Length of the repetition levels.
     *
     * @var int
     */
    public $repetition_levels_byte_length;

    /**
     * Optional statistics for the data in this page *.
     *
     * @var Statistics
     */
    public $statistics;

    public function __construct($vals = null)
    {
        if (is_array($vals)) {
            if (isset($vals['num_values'])) {
                $this->num_values = $vals['num_values'];
            }

            if (isset($vals['num_nulls'])) {
                $this->num_nulls = $vals['num_nulls'];
            }

            if (isset($vals['num_rows'])) {
                $this->num_rows = $vals['num_rows'];
            }

            if (isset($vals['encoding'])) {
                $this->encoding = $vals['encoding'];
            }

            if (isset($vals['definition_levels_byte_length'])) {
                $this->definition_levels_byte_length = $vals['definition_levels_byte_length'];
            }

            if (isset($vals['repetition_levels_byte_length'])) {
                $this->repetition_levels_byte_length = $vals['repetition_levels_byte_length'];
            }

            if (isset($vals['is_compressed'])) {
                $this->is_compressed = $vals['is_compressed'];
            }

            if (isset($vals['statistics'])) {
                $this->statistics = $vals['statistics'];
            }
        }
    }

    public function getName()
    {
        return 'DataPageHeaderV2';
    }

    public function read($input)
    {
        $xfer = 0;
        $fname = null;
        $ftype = 0;
        $fid = 0;
        $xfer += $input->readStructBegin($fname);

        while (true) {
            $xfer += $input->readFieldBegin($fname, $ftype, $fid);

            if ($ftype == TType::STOP) {
                break;
            }

            switch ($fid) {
                case 1:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->num_values);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 2:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->num_nulls);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 3:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->num_rows);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 4:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->encoding);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 5:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->definition_levels_byte_length);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 6:
                    if ($ftype == TType::I32) {
                        $xfer += $input->readI32($this->repetition_levels_byte_length);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 7:
                    if ($ftype == TType::BOOL) {
                        $xfer += $input->readBool($this->is_compressed);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;
                case 8:
                    if ($ftype == TType::STRUCT) {
                        $this->statistics = new Statistics();
                        $xfer += $this->statistics->read($input);
                    } else {
                        $xfer += $input->skip($ftype);
                    }

                    break;

                default:
                    $xfer += $input->skip($ftype);

                    break;
            }
            $xfer += $input->readFieldEnd();
        }
        $xfer += $input->readStructEnd();

        return $xfer;
    }

    public function write($output)
    {
        $xfer = 0;
        $xfer += $output->writeStructBegin('DataPageHeaderV2');

        if ($this->num_values !== null) {
            $xfer += $output->writeFieldBegin('num_values', TType::I32, 1);
            $xfer += $output->writeI32($this->num_values);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->num_nulls !== null) {
            $xfer += $output->writeFieldBegin('num_nulls', TType::I32, 2);
            $xfer += $output->writeI32($this->num_nulls);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->num_rows !== null) {
            $xfer += $output->writeFieldBegin('num_rows', TType::I32, 3);
            $xfer += $output->writeI32($this->num_rows);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->encoding !== null) {
            $xfer += $output->writeFieldBegin('encoding', TType::I32, 4);
            $xfer += $output->writeI32($this->encoding);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->definition_levels_byte_length !== null) {
            $xfer += $output->writeFieldBegin('definition_levels_byte_length', TType::I32, 5);
            $xfer += $output->writeI32($this->definition_levels_byte_length);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->repetition_levels_byte_length !== null) {
            $xfer += $output->writeFieldBegin('repetition_levels_byte_length', TType::I32, 6);
            $xfer += $output->writeI32($this->repetition_levels_byte_length);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->is_compressed !== null) {
            $xfer += $output->writeFieldBegin('is_compressed', TType::BOOL, 7);
            $xfer += $output->writeBool($this->is_compressed);
            $xfer += $output->writeFieldEnd();
        }

        if ($this->statistics !== null) {
            if (!is_object($this->statistics)) {
                throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
            }
            $xfer += $output->writeFieldBegin('statistics', TType::STRUCT, 8);
            $xfer += $this->statistics->write($output);
            $xfer += $output->writeFieldEnd();
        }
        $xfer += $output->writeFieldStop();
        $xfer += $output->writeStructEnd();

        return $xfer;
    }
}
