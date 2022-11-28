<?php

namespace SB\File\Handler;

abstract class Csv
{
    /** @var string $delimiter */
    protected $delimiter = ';';

    /** @var string $enclosure */
    protected $enclosure = '"';

    /** @var string $escape */
    protected $escape = '\\';

    /** @var int $options */
    protected $options = 0;

    /** @var array $columns */
    protected $columns = [];

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->escape;
    }

    /**
     * @param int $option
     * @return bool
     */
    public function hasOption(int $option): bool
    {
        return (bool)$this->options & $option;
    }

    /**
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param string $delimiter
     * @return self
     */
    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @param string $enclosure
     * @return self
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @param string $escape
     * @return self
     */
    public function setEscape(string $escape): self
    {
        $this->escape = $escape;

        return $this;
    }

    /**
     * @param int $option
     * @param bool $enabled
     *
     * @return self
     */
    public function setOption(int $option, bool $enabled): self
    {
        $this->options = $enabled
            ? ($this->options | $option)
            : ($this->options & ~$option);

        return $this;
    }
}