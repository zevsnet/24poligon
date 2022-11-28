<?php

namespace SB\File\Csv;

use SB\File\Handler\Csv as AbstractCsvHandler;

class Reader extends AbstractCsvHandler
{
    const OPTION_FIRST_LINE_HEADER = 1;
    const OPTION_FETCH_NO_ASSOC = 2;

    /** @var resource $fileHandle */
    protected $fileHandle;

    /** @var int $length */
    protected $length;

    /**
     * @param resource $fileHandle
     * @param int $length
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($fileHandle, int $length = 0, string $delimiter = ';', string $enclosure = '"', string $escape = '\\')
    {
        $this->fileHandle = $fileHandle;

        $this->setLength($length)
            ->setDelimiter($delimiter)
            ->setEnclosure($enclosure)
            ->setEscape($escape);
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return self
     */
    public function setLength($length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return \Generator
     */
    public function read(): \Generator
    {
        fseek($this->fileHandle, 0);

        if ($this->hasOption(static::OPTION_FIRST_LINE_HEADER)) {
            $cols = $this->getCsv();

            if (\is_array($cols)) {
                $this->setColumns($cols);
            }
        }

        while (($data = $this->getCsv()) !== false) {
            yield $this->getOutput($data);
        }
    }

    /**
     * @return mixed
     * @uses fgetcsv()
     */
    protected function getCsv()
    {
        return fgetcsv(
            $this->fileHandle,
            $this->length,
            $this->delimiter,
            $this->enclosure,
            $this->escape
        );
    }

    /**
     * @param mixed $data
     * @return mixed
     *
     * @uses optimizeDataLength()
     */
    protected function getOutput($data)
    {
        if (!$data || !\is_array($data)) {
            return $data;
        }

        if (empty($this->columns) || $this->hasOption(static::OPTION_FETCH_NO_ASSOC)) {
            return $data;
        }

        $data = $this->optimizeDataLength($data, \count($this->columns));

        return array_combine($this->columns, $data);
    }

    /**
     * @param array $data
     * @param int $length
     * @return array
     */
    protected function optimizeDataLength(array $data, $length): array
    {
        $dataLength = \count($data);

        if ($dataLength > $length) {
            return \array_slice($data, 0, $length);
        }

        if ($dataLength < $length) {
            return array_merge(
                $data,
                array_fill(0, ($length - $dataLength), null),
                null
            );
        }

        return $data;
    }

    public function __destruct()
    {
        $this->fileHandle = null;
    }
}
