<?php

namespace SB\Bitrix;


abstract class IBlockBase
{
    /** @var int $id */
    protected $id = 0;


    /** @var string $code */
    protected $code = '';

    /** @var string $name */
    protected $name = '';


    /** @var array $arFields */
    protected $arFields;

    /**
     * @return bool
     */
    abstract public function isExist(): bool;

    /**
     * @param int $id
     * @return mixed
     */
    abstract public static function getById(int $id);

    /**
     * @param string $code
     * @return mixed
     */
    abstract public static function getByCode(string $code);

    /**
     * @return mixed
     */
    abstract public function create();

    /**
     * @return mixed
     */
    abstract public function update();

    /**
     * Добавляет поле (используется при создании и обновлении)
     * @param $key
     * @param $value
     * @return IBlockBase
     */
    public function setField($key, $value): self
    {
        $this->arFields[$key] = $value;
        return $this;
    }

    /**
     * Добавляет поля (используется при создании и обновлении)
     * @param array $fields
     * @return IBlockBase
     */
    public function setFields(array $fields): self
    {
        foreach ($fields as $key => $field) {
            $this->arFields[$key] = $field;
        }

        return $this;
    }


    /**
     * Возращает поле по ключу
     * @param $key
     * @return mixed
     */
    public function getField($key)
    {
        return $this->arFields[$key];
    }

    /**
     * Возвращает поля инфоблока
     * @return array
     */
    public function getFields(): array
    {
        return $this->arFields;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

}