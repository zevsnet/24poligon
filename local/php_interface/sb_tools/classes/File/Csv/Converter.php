<?php

namespace SB\File\Csv;

class Converter
{
    /** @var callable $converter */
    protected $converter;

    /** @var array $map */
    protected $map = [];

    /** @var Reader $reader */
    protected $reader;

    /** @var \Generator $readSrc */
    protected $readSrc;

    /** @var Writer $writer */
    protected $writer;

    /**
     * @param Reader $reader
     * @param Writer $writer
     * @param array $map
     * @param callable $converter
     */
    public function __construct(Reader $reader, Writer $writer, array $map = [], callable $converter = null)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->map = $map;
        $this->converter = $converter;
    }

    /**
     * @return array
     */
    public function getConversionMap(): array
    {
        return $this->map;
    }

    /**
     * @return Reader
     */
    public function getReader(): Reader
    {
        return $this->reader;
    }

    /**
     * @return Writer
     */
    public function getWriter(): Writer
    {
        return $this->writer;
    }

    /**
     * @return int
     */
    public function process(): int
    {
        $i = 0;

        while ($this->processRow() !== false) {
            $i++;
        }

        return $i;
    }

    /**
     * @return array|false
     */
    public function processRow()
    {
        if (null === $this->readSrc) {
            $this->readSrc = $this->reader->read();
        }

        $incoming = $this->readSrc->current();
        if ($incoming === false || null === $incoming) {
            return false;
        }

        $out = $this->convertValue($incoming);
        if (null !== $this->writer) {
            $this->writer->write($out);
        }

        $this->readSrc->next();

        return $out;
    }

    /**
     * @param array $incoming
     * @return array
     */
    protected function convertValue(array $incoming): array
    {
        $out = $this->convertByMap($incoming);
        if (null !== $this->converter) {
            $out2 = $this->convertByCallback($incoming, $out);
            if (!empty($out2)) {
                $out = array_replace($out, $out2);
            }
        }

        return $out;
    }

    /**
     * @param array $incoming
     * @return array
     */
    protected function convertByMap(array $incoming): array
    {
        $result = [];

        foreach ($this->map as $from => $to) {
            if (!array_key_exists($from, $incoming)) {
                continue;
            }

            $result[$to] = $incoming[$from];
        }

        return $result;
    }

    /**
     * @param array $incoming
     * @param array &$out
     * @return array
     */
    protected function convertByCallback(array $incoming, array &$out): array
    {
        if (null !== $this->converter) {
            $result = \call_user_func($this->converter, $incoming, $out, $this);
        }

        return (null !== $result && \is_array($result)) ? $result : [];
    }

    public function __destruct()
    {
        $this->readSrc = null;
        $this->reader = null;
        $this->writer = null;
    }
}
